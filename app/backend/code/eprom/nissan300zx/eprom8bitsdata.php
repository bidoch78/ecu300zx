<?php 

declare(strict_types=1);

namespace ecu300zx\eprom\nissan300zx;

use ecu300zx\wrappers\epromData;
use ecu300zx\eprom\nissan300zx\eprom8bits;

class eprom8bitsData extends epromData {

    protected ?array $_entry = null;

    function __construct(array $entry, eprom8bits $eprom) {
        parent::__construct($entry["key"], $eprom);
        $this->_entry = $entry;
        $this->readData();
    }

    function readData() {
        
        $hexAddr = $this->_entry["data"]["offset"];

        if (isset($this->_entry["data"]["isword"]) && $this->_entry["data"]["isword"] === true) {
            $this->setValue($this->getHexWordFromIntPtr(hexdec($hexAddr)));
            return;
        }

        $this->setValue($this->getHexCharFromIntPtr(hexdec($hexAddr)));

    }

    function getReadableValue(): mixed {

        $signed = (isset($this->_entry["data"]["signed"]) && $this->_entry["data"]["signed"] === true);

        $val = $this->getNumFromHex($this->getValue(), false, false);

        if (isset($this->_entry["data"]["*by"])) $val *= $this->_entry["data"]["*by"];

        return $val;

    }

    function getParameters(): mixed {
        return null;
    }

}

?>