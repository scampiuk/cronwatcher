<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 07/10/14
 * Time: 15:02
 */

class logHelper {

    function error($msg, $siteId=false, $cronId=false, $notes=false, $status=false){
        echo "[error] " . $msg ." ( " . $siteId . ", $cronId , $notes , $status )";
    }

    function info($msg, $siteId=false, $cronId=false, $notes=false, $status=false){
        echo "[info] " . $msg ." ( " . $siteId . ", $cronId , $notes , $status )";
    }
} 