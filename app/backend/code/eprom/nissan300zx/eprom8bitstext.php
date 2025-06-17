<?php 

declare(strict_types=1);

namespace ecu300zx\eprom\nissan300zx;

use ecu300zx\wrappers\epromText;
use ecu300zx\eprom\nissan300zx\eprom8bits;
use ecu300zx\eprom\nissan300zx\eprom8bitsData;

class eprom8bitsText extends epromText {

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

        $rows = [];
        for($iCell = 0; $iCell < $this->_entry["data"]["nbhexvalues"]; $iCell++) {

            if ($isword) {
                $rows[] = $this->getHexWordFromIntPtr($fromAddr);
            }
            else {
                $rows[] = $this->getHexCharFromIntPtr($fromAddr);
            }

            $fromAddr+=($isword?2:1);

        }

        $this->setValue($rows);

    }

    function getReadableValue(): mixed {      

        $hexvalues = $this->getValue();

        $part1 = sprintf("%02x%02x%02x%02x%02x%02x%02x", hexdec($hexvalues[0]), hexdec($hexvalues[1]), hexdec($hexvalues[2]), hexdec($hexvalues[3]), hexdec($hexvalues[4]), hexdec($hexvalues[5]), hexdec($hexvalues[6]));
        $part2 = sprintf("%02x%02x%02x%02x", hexdec($hexvalues[8]), hexdec($hexvalues[9]), hexdec($hexvalues[10]), hexdec($hexvalues[11]));
        $part3 = sprintf("%02x%02x%02x%02x%02x", hexdec($hexvalues[12]), hexdec($hexvalues[13]), hexdec($hexvalues[14]), hexdec($hexvalues[15]), hexdec($hexvalues[16]));
        $part4 = sprintf("%c%c%c%c", hexdec($hexvalues[17]), hexdec($hexvalues[18]), hexdec($hexvalues[19]), hexdec($hexvalues[20]));

        return $part1 . "-" . $part2 . " " . $part3 . "-" . $part4 ;

    }

    function getParameters(): mixed {

        return [];

    }

}

?>