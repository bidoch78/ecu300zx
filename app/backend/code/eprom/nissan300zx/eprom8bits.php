<?php 

declare(strict_types=1);

namespace ecu300zx\eprom\nissan300zx;

use ecu300zx\wrappers\epromWrapper;
use ecu300zx\wrappers\epromData;

use ecu300zx\eprom\nissan300zx\eprom8bitsData;
use ecu300zx\eprom\nissan300zx\eprom8bitsMap;
use ecu300zx\eprom\nissan300zx\eprom8bitsText;

class eprom8bits extends epromWrapper {

    public function __construct() {
        $this->addEntry('ROMIDNUMBER', 'ROM Id', [ 'offset' => "6AC0", 'type' => "text", 'nbhexvalues' => 22, 'isromid' => true ]);
        $this->addEntry('FEEDBACKCTRL', 'Feedback Control', [ 'offset' => "7F91", 'type' => "value" ]);
        $this->addEntry('REVLIMITER', 'Rev Limiter (rpm)', [ 'offset' => "7FB4", 'type' => "value", "*by" => 50 ]);
        $this->addEntry('VTCRELEASE', 'VTC Release (rpm)', [ 'offset' => "79F6", 'type' => "value", "*by" => 50 ]);
        $this->addEntry('SPEEDLIMITER', 'Speed Limiter (kmh)', [ 'offset' => "7FA5", 'type' => "value", "*by" => 2 ]);
        $this->addEntry('INJLATENCY', 'Injector Latency (ms)', [ 'offset' => "7F88", 'type' => "value" ]);
        $this->addEntry('KVALUE', 'K Value', [ 'offset' => "7F2B", 'type' => "value", "isword" => true ]);
        $this->addEntry('VTCRELEASE', 'VTC Release (rpm)', [ 'offset' => "79F6", 'type' => "value", "*by" => 50 ]);
        $this->addEntry('KNOCKSENSOR', 'Knock Sensor Map', [ 'offset' => "7FE0", 'type' => "map", 'rows' => 1, 'cellsbyrow' => 8 ]);
        $this->addEntry('TTPMIN', 'Total Theoretical Pulsewidth Minimum Map', [ 'offset' => "7E80", 'type' => "map", 'rows' => 1, 'cellsbyrow' => 16 ]);
        $this->addEntry('TTPMAX', 'Total Theoretical Pulsewidth Maximum Map', [ 'offset' => "7E90", 'type' => "map", 'rows' => 1, 'cellsbyrow' => 16 ]);
        $this->addEntry('TPSCALETIMING', 'TP Scale Timing', [ 'offset' => "7B10", 'type' => "map", 'rows' => 1, 'cellsbyrow' => 16 ]);
        $this->addEntry('TPSCALEFUEL', 'TP Scale Fuel', [ 'offset' => "7AF0", 'type' => "map", 'rows' => 1, 'cellsbyrow' => 16 ]);
        $this->addEntry('RPMSCALETIMING', 'RPM Scale Timing (rpm)', [ 'offset' => "7B20", 'type' => "map", 'rows' => 1, 'cellsbyrow' => 16, "*by" => 50 ]);
        $this->addEntry('RPMSCALEFUEL', 'RPM Scale Timing (rpm)', [ 'offset' => "7B00", 'type' => "map", 'rows' => 1, 'cellsbyrow' => 16, "*by" => 50 ]);
        $this->addEntry('PTIMING', 'Primary Timing Map', [ 'offset' => "7800", 'type' => "map", 'rows' => 16, 'cellsbyrow' => 16, 'signed' => true, 
                                                            "yaxis" => [ "is" => "ref", "to" => "RPMSCALETIMING"],
                                                            "xaxis" => [ "is" => "ref", "to" => "TPSCALETIMING" ],
                                                            "gradient" => [ [ "c" => "b", "v" => -5],
                                                                            [ "c" => "bl", "v" => 5],
                                                                            [ "c" => "g", "v" => 25], 
                                                                            [ "c" => "y", "v" => 35 ], 
                                                                            [ "c" => "r", "v" => 45 ] ]
                                                        ]);
        $this->addEntry('KNOCKTIMING', 'Knock Timing Map', [ 'offset' => "7C00", 'type' => "map", 'rows' => 16, 'cellsbyrow' => 16, 'signed' => true, 
                                                            "yaxis" => [ "is" => "ref", "to" => "RPMSCALETIMING"], 
                                                            "xaxis" => [ "is" => "ref", "to" => "TPSCALETIMING" ],
                                                            "gradient" => [ [ "c" => "b", "v" => -5],
                                                                            [ "c" => "bl", "v" => 5],
                                                                            [ "c" => "g", "v" => 25], 
                                                                            [ "c" => "y", "v" => 35 ], 
                                                                            [ "c" => "r", "v" => 45 ] ]
                                                       ]);
        $this->addEntry('PFUELMAP', 'Primary Fuel Map', [ 'offset' => "7D00", 'type' => "map", 'rows' => 16, 'cellsbyrow' => 16, 'signed' => true, "isAFR" => true,
                                                            "yaxis" => [ "is" => "ref", "to" => "RPMSCALEFUEL"],
                                                            "xaxis" => [ "is" => "ref", "to" => "TPSCALEFUEL"],
                                                             "gradient" => [ [ "c" => "b", "v" => 10],
                                                                            [ "c" => "bl", "v" => 12],
                                                                            [ "c" => "g", "v" => 14.7], 
                                                                            [ "c" => "y", "v" => 15.1 ], 
                                                                            [ "c" => "r", "v" => 15.9 ] ]
                                                        ]);
        $this->addEntry('WATERWARMUP', 'Water Warm Up', [ 'offset' => "7B30", 'type' => "map", 'rows' => 2, 'cellsbyrow' => 16 ]);
        $this->addEntry('VQTABLE', 'VQ Table', [ 'offset' => "7A70", 'type' => "map", 'rows' => 2, 'cellsbyrow' => 16, "isword" => true ]);
        $this->addEntry('AIRFLOWLIMITER', 'Air Flow Limiter', [ 'offset' => "7960", 'type' => "map", 'rows' => 1, 'cellsbyrow' => 8, "isword" => true ]);
        $this->addEntry('IGNITIONDWELL', 'Ignition Dwell Duty', [ 'offset' => "7B60", 'type' => "map", 'rows' => 2, 'cellsbyrow' => 16 ]);
    }

    public function getVersion(): string { return "1.0.010"; }

    public function loadData(string $file): bool {

        if (!file_exists($file)) return false;

        $filesize = filesize($file);
        $fp = fopen($file, "rb");
        $binary = fread($fp, $filesize);
        fclose($fp);

        $this->_data = unpack(sprintf('C%d', $filesize), $binary);

        return true;

    }

    public function readData(string $name): null | epromData {
        
        $entry = $this->getEntry($name);
        if (!$entry) return null;

        switch($entry["data"]["type"]) {
            case "value": return new eprom8bitsData($entry, $this);
            case "map": return new eprom8bitsMap($entry, $this);
            case "text": return new eprom8bitsText($entry, $this);
        }

        return null;

    }

}

?>