<?php 

declare(strict_types=1);

namespace ecu300zx\eprom\nissan300zx;

use ecu300zx\wrappers\epromMap;
use ecu300zx\eprom\nissan300zx\eprom8bits;
use ecu300zx\eprom\nissan300zx\eprom8bitsData;

class eprom8bitsMap extends epromMap {

    protected ?array $_entry = null;

    function __construct(array $entry, eprom8bits $eprom) {
        parent::__construct($entry["key"], $eprom);
        $this->_entry = $entry;
        $this->readData();
    }

    function readData() {

        $hexAddr = $this->_entry["data"]["offset"];
        $fromAddr = hexdec($hexAddr);
        $isword = (isset($this->_entry["data"]["isword"]) && $this->_entry["data"]["isword"] === true);
    
        $map = [];
        for($iRow = 0; $iRow < $this->_entry["data"]["rows"]; $iRow++) {
            $row = [];
            for($iCell = 0; $iCell < $this->_entry["data"]["cellsbyrow"]; $iCell++) {

                if ($isword) {
                    $row[] = $this->getHexWordFromIntPtr($fromAddr);
                }
                else {
                    $row[] = $this->getHexCharFromIntPtr($fromAddr);
                }

                $fromAddr+=($isword?2:1);

            }
            $map[] = $row;
        }

        $this->setValue($map);

    }

    function getReadableValue(): mixed {      

        $isword = (isset($this->_entry["data"]["isword"]) && $this->_entry["data"]["isword"] === true);
        $signed = (isset($this->_entry["data"]["signed"]) && $this->_entry["data"]["signed"] === true);
        $multiby = (isset($this->_entry["data"]["*by"])) ? $this->_entry["data"]["*by"] : 1;

        $map = $this->getValue();
    
        $newMap = [];
        
        foreach($map as $row) {
            $nrow = [];
            $nknock = [];
            foreach($row as $cell) {
                $nrow[] = $this->getNumFromHex($cell, $isword, $signed) * $multiby;
            }
            $newMap[] = $nrow;
        }

        return $newMap;

    }

    function getParameters(): mixed {

        $data = [ 'r' => $this->_entry["data"]["rows"], 'c' => $this->_entry["data"]["cellsbyrow"], "xaxis" => [], "yaxis" => [] ];

        //default
        $addAxisValues = false;
        if (isset($this->_entry["data"]["yaxis"]["is"])) {
            
            switch($this->_entry["data"]["yaxis"]["is"]) {
                case "ref":
                    $scale = $this->getEprom()->readData($this->_entry["data"]["yaxis"]["to"]);
                    if ($scale && $scale->getType() == "map") {
                        $scaleData = $scale->getReadableValue();
                        $data["yaxis"] = $scaleData[0];
                        $addAxisValues = true;
                    }
                    break;
            }

        }
        
        //default
        if (!$addAxisValues) {
            for($iRow = 0; $iRow < $data["r"]; $iRow++) $data["yaxis"][] = $iRow+1;
        }
    
        //default
        $addAxisValues = false;
        if (isset($this->_entry["data"]["xaxis"]["is"])) {
            
            switch($this->_entry["data"]["xaxis"]["is"]) {
                case "ref":
                    $scale = $this->getEprom()->readData($this->_entry["data"]["xaxis"]["to"]);
                    if ($scale && $scale->getType() == "map") {
                        $scaleData = $scale->getReadableValue();
                        $data["xaxis"] = $scaleData[0];
                        $addAxisValues = true;
                    }
                    break;
            }

        }
        
        //default
        if (!$addAxisValues) {
            for($iCol = 0; $iCol < $data["c"]; $iCol++) $data["xaxis"][] = $iCol+1;
        }

        return $data;

    }

}

?>