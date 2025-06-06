<?php 

declare(strict_types=1);

namespace ecu300zx\wrappers;

use ecu300zx\wrappers\epromData;

abstract class epromWrapper {

    protected mixed $_data = null;
    protected ?array $_entries = null;

    public abstract function getVersion():string;
    public abstract function loadData(string $file): bool;

    public function addEntry(string $key, string $name, array $data = null) {
        if (!$this->_entries) $this->_entries = [];
        $this->_entries[$key] = [ 'key' => $key, 'name' => $name, 'data' => $data ];
    }

    public function getEntries(): array {

        if (!$this->_entries) return [];

        $listOfValues = [];

        foreach($this->_entries as $v) {
            $listOfValues[] = [ "key" => $v["key"], "name" => $v["name"] ];
        }

        return $listOfValues;

    }

    public function getEntry(string $name): null | array {
        return (!$this->_entries && !isset($this->_entries[$name])) ? null : $this->_entries[$name];
    }

    public abstract function readData(string $name): null | epromData;

    public function getData(): mixed {
        return $this->_data;
    }

}

?>