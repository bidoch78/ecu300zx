<?php 

declare(strict_types=1);

namespace ecu300zx\controllers;

use ecu300zx\controllers\controller;
use ecu300zx\core;
// use megaraid\wrappers\dataWrapper;
// use megaraid\wrappers\DATE_FORMAT;

class version extends controller {

    public function getVersion(): array {

        $version = ["api" => core::getVersion() ];

//         $wrapperData = core::getWrapper()->getVersion();
//         $wData = $wrapperData->getData();

//         if (count($wData) == 1) {

//             foreach($wData[0] as $info) {
//                 $info = trim($info);
//                 $ver = stripos($info,"ver");
//                 if ($ver!==false) {
//                     $version["cli"]["name"] = trim(substr($info, 0, $ver));
//                     $detail = explode(" ", trim(substr($info, $ver)));
//                     if (strcasecmp("ver", $detail[0]) == 0 && count($version) == 2) {
//                         $version["cli"]["version"] = $detail[1];
//                         array_shift($detail);
//                         array_shift($detail);
//                         $version["cli"]["date"] = dataWrapper::formatDateTime(implode(" ", $detail), DATE_FORMAT::DATE_DDDMMSEPYYY);
//                     }
//                 }
//                 else {
//                     $cpy = stripos($info,"copyright");
//                     if ($cpy) $version["cli"]["copyright"] = $info;
//                 }
//             }

//         }

        $version["copyright"] = "@" . date("Y") . " bidoch28";
        $version["options"] = [];
//         $version["options"]["displaySATA"] = core::getSataDevices()->isEnabled();

//         //if (core::getSataDevices()->isEnabled()) {
//         //     $version["satadrives"] = [ 'phyicaldrives' => core::getSataDevices()->getPhysicalDrive() ];
//         // }

//         $version["wrapper"] = [ 'class' => core::getWrapper()->getName(), 'version' => core::getVersion() ];
//         $version["code"] = $wrapperData->getCode();

        return $version;

    }

}

?>