<?php 

    declare(strict_types=1);

    $filename = "../config/M305AEA7.BIN";
    #$filename = "../config/AshSpec-BA-1.BIN";
    #$filename = "../config/JWT-90-TT-AT-370.bin";
    $filesize = filesize($filename);
    $fp = fopen($filename, "rb");
    $binary = fread($fp, $filesize);
    fclose($fp);

    echo sprintf('C%d', $filesize);

    $unpacked = unpack(sprintf('C%d', $filesize), $binary);
    
    $hexAddr = "79F6";
    echo "\nvtc: " . $unpacked[hexdec($hexAddr)+1] . " - " . $unpacked[hexdec($hexAddr)+1] * 50;
    $hexAddr = "7FB4";
    echo "\nrev limiter: " . $unpacked[hexdec($hexAddr)+1] . " - " . $unpacked[hexdec($hexAddr)+1] * 50;
    $hexAddr = "7FA5";
    echo "\ntop speed limiter: " . $unpacked[hexdec($hexAddr)+1] . " - " . $unpacked[hexdec($hexAddr)+1] * 2;
    $hexAddr = "7B2F";
    echo "\nRpm Scales: " . $unpacked[hexdec($hexAddr)+1];
     $hexAddr = "7B0F";
    echo "\nRpm Scales: " . $unpacked[hexdec($hexAddr)+1];

    // $ttpmax = "";
    // $hexAddr = "7E80";
    // for($i = 1; $i < 17; $i++) {
    //     $ttpmax .= " " . dechex($unpacked[hexdec($hexAddr)+$i]);
    // }
    // echo "\nTTP Min: " . $ttpmax;

    // $ttpmax = "";
    // $hexAddr = "7B2F";
    // for($i = 1; $i < 17; $i++) {
    //     $ttpmax .= " " . dechex($unpacked[hexdec($hexAddr)+$i]) . " ," . $unpacked[hexdec($hexAddr)+$i];
    // }
    // echo "\nData: " . $ttpmax;

    echo "\n***";


    // for($i = 32400; $i < 32400 + 16; $i++) {
    //     $value = $unpacked[$i];
    //     echo $value . "\n";
    // }

?>