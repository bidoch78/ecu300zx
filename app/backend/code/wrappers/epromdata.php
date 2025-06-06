<?php 

declare(strict_types=1);

namespace ecu300zx\wrappers;
use ecu300zx\wrappers\epromWrapper;

abstract class epromData {

    protected ?string $_name = null;
    protected mixed $_value = null;
    protected ?epromWrapper $_eprom = null;

    function __construct(string $name, epromWrapper $eprom) {
        $this->_name = $name;
        $this->_eprom = $eprom;
    }

    function getEprom(): epromWrapper {
        return $this->_eprom;
    }

    function getValue(): mixed {
        return $this->_value;
    }

    function setValue(mixed $value) {
        $this->_value = $value;
    }

    function getType(): string {
        return "value";
    }

    abstract function getReadableValue(): mixed;
    abstract function getParameters(): mixed;

    function getHexCharFromIntPtr(int $ptr) {
        return dechex($this->getEprom()->getData()[$ptr+1]);
    }

    function getHexWordFromIntPtr(int $ptr) {
 
        $msb = $this->getEprom()->getData()[$ptr+1];
        $lsb = $this->getEprom()->getData()[$ptr+2];

        return dechex(($msb * 256) + $lsb);

    }

    function getNumFromHex(string $hex, bool $isword, bool $signed = false):int {

        if (!$signed) return hexdec($hex);

        $bitSigned = false;
        $val = hexdec($hex);
        if ($isword) {
            $bitSigned = !!($val & 32768);
            $val = $val & 32767;
        }
        else {
            $bitSigned = !!($val & 128);
            $val = $val & 127;
        }

        return $bitSigned ? -$val : $val;

    }

    function getArray(): null | array {
        return [ "is" => $this->getType(),
                    "name" => $this->_name,
                    "value" => [ 'source' => $this->getValue(), "readable" => $this->getReadableValue() ],
                    "_p" => $this->getParameters() ];
    }

}

?>