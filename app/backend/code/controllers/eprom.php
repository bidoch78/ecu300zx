<?php 

declare(strict_types=1);

namespace ecu300zx\controllers;

use ecu300zx\controllers\controller;
use ecu300zx\core;
// use megaraid\wrappers\dataWrapper;
// use megaraid\controllers\virtualdrive;
// use megaraid\controllers\physicaldrive;
// use megaraid\wrappers\dataStructureWrapper;
// use megaraid\wrappers\DATE_FORMAT;

class eprom extends controller {

    public function getall(): array {

        $info = [   'class' => $this->request->getAnyParameter("class"), 
                    'eprom' => $this->request->getAnyParameter("eprom"),
                    'file' => $this->request->getAnyParameter("file"), 'data' => false ];

        //Load eprom wrapper
        $epromReader = null;
        try {
            $epromReaderClass = "ecu300zx\\eprom\\" . $info["class"] . "\\eprom" . $info["eprom"];
            $epromReader = new $epromReaderClass();
        }
        catch(\Exception $ex) {
            $info["error"] = $ex->getMessage();
        }
      
        $info["data"] = $epromReader->loadData("../config/" . $info["file"]);
        if ($info["data"]) {

            $info["data"] = [];
            $info["data"]["available"] = $epromReader->getEntries();
            $info["data"]["detail"] = [];

            foreach($info["data"]["available"] as $v) {
                $data = $epromReader->readData($v["key"]);
                $info["data"]["detail"][$v["key"]] = ($data ? $data->getArray() : null);
            }

        }

        return $info;

    }

}

?>