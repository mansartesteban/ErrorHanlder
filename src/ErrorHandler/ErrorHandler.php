<?php

namespace shining_orm;

use DateTime;
use Exception;

class ErrorHandler {
    
    private static $created = false;
    private static $options = [];
    private static $userErrorTypes = [];
    private static $logErrorTypes = [];
    
    public static function __call() {
        if (!self::$created) {
            throw new LogicException("La classe " . __CLASS__ . " n'a pas été initialisée");
        }
    }
    
    /**
     * 
     */
    public static function init(array $options = []) {
        self::$options = [
            "logDir" => "/log"
        ];
        self::$options = $options + self::$options;
        self::$created = true;
        
    }
    
    public static function isCreated() {
        return (self::$created);
    }
    
    public static function userError(string $type = "", string $msg = "") {
        
        if ($type != "" && $msg != "") {
            
            if (in_array(strtoupper($type), self::$userErrorTypes)) {
                $_SESSION[strtolower($type) . "Error"] = $msg;
            }
          
        }
        
    }
    
    /**
     * 
     * @param string $type Type d'erreur (Nom du dossier)
     * @param Exception $error L'exception
     * @param type $moreParams Paramètres supplémentaires
     */
    public static function log(string $type = "", Exception $error = null) {
        
        if ($error != null) {
            $now = new DateTime();
            $logFilename = $now->format("Y-m-d") . ".log";
            $logDir = self::$options["logDir"];
            
            $dir = $logDir . "/" . strtolower(($type != "") ? ($type) : ("unknown")) . "/";
            if (!is_dir($dir)) {
                @mkdir($dir);
            }
            file_put_contents($dir . $logFilename, self::formatError($error, $type), FILE_APPEND);
        }
    }
    
//    public static function cURLDump(string $server = "", string $from = "", string $to = "", $params = null, $response = null) :void
//    {
//        if (!empty($server) && !empty($from) && !empty($to) && !empty($params)) {
////            ob_start();
//            ?>
                <!--<pre>-->
                [//<?= new MyDateTime() ?>] Requête cURL : <?= $server ?><br/>
                <b>FROM:</b> //<?= $from ?><br/>
                <b>TO:</b> //<?= $to ?><br/>
                <b>PARAMS:</b><pre>//<?= var_dump($params) ?></pre><br/>
                <b>REPONSE:</b><pre>//<?= var_dump($response) ?></pre><br/>
                <!--</pre>-->
            //<?php
////            $display = ob_get_clean();
////            echo $display;
//        }
//        
//    }
    
    private static function formatError(Exception $ex, $type = "unknown") {
        
        ob_start();
        ?>
                <div class="error_log">
                    [<?= new MyDateTime() ?>] (<?= $_SERVER["HTTP_HOST"] ?>)<br/>
                    <b>Type: </b> <?= $type ?><br/>
                    <b>FILE: </b><?= $ex->getFile() ?>:<?= $ex->getLine()?> - <?= $ex->getCode() ?><br/>
                    <b>MSG: </b><?= $ex->getMessage() ?><br/>
                    <?php if (!empty($more)) : ?>
                    <b>ADDITIONAL PARAMETERS: </b><pre><?= var_dump() ?></pre>
                    <?php endif; ?>
                    <br/><br/>
                </div>
        <?php
        $err = ob_get_clean();
        return $err . PHP_EOL;
    }
    
}