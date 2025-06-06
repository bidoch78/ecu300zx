<?php

    require_once(__DIR__ . "/../vendor/autoload.php");

    spl_autoload_register(function (string $class) {

        $path = explode("\\", $class);
        $findRootSrc = false;
        foreach(explode("\\", $class) as $index => $part) {

            if ($part == "ecu300zx") {
                $path[$index] = __DIR__;
                $findRootSrc = true;
            }
            else {
                $path[$index] = strtolower($part);
            }

        }

        $classPath = implode("/", $path);

        if (!$findRootSrc) $classPath = __DIR__ . "/" . $classPath;

        $classPath = str_replace("\\", "/", $classPath . ".php");

        if (!file_exists($classPath)) { 
            throw new \Exception("class: '" . $classPath . "' not exists");
        }

        require_once($classPath);

    });

?>