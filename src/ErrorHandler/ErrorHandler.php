<?php

namespace shining_orm;

use DateTime;
use Exception;

/**
 * Classe de gestion de log d'erreur.\n
 * Visite https://github.com/mansartesteban/ErrorHandler pour consulter la documentation
 */
class ErrorHandler {
    
    private static $created = false;
    private static $options = [];
    private static $documentation = "Pour plus d'informations, veuillez consulter la documentation disponible sur : https://github.com/mansartesteban/ErrorHandler";
    
    const START_DELIMITER = "##########";
    const END_DELIMITER = "__________";
    
    
    /* ===== PUBLIC METHODS ===== */
    
    /**
     * @param $options Tableau de paramètres
     */
    public static function init(array $options = []) {
        self::$options = [
            "logDir" => "/log",
            "formatDateFilename" => "Y-m-d",
            "fileExtension" => ".log"
        ];
        self::$options = $options + self::$options;
        self::removeTrailingSlash(self::$options["logDir"]);
        self::$created = true;
    }
    
    /**
     * @return bool isCreated Return true si la fonction a déjà été initialisée, false sinon
     */
    public static function isCreated() {
        return (self::$created);
    }
    
    /**
     * @param string $type Type d'erreur (Nom du dossier)
     * @param Exception $error L'exception
     * @param type $moreParams Paramètres supplémentaires
     */
    public static function log(string $type = "", Exception $error = null) {
        if (self::$created) {
            if ($error != null) {
                $now = new DateTime();
                $logFilename = $now->format(self::$options["formatDateFilename"]) . self::$options["fileExtension"];
                $logDir = self::$options["logDir"];

                $dir = $logDir . "/" . strtolower(($type != "") ? ($type) : ("unknown")) . "/";
                if (!is_dir($dir)) {
                    @mkdir($dir);
                }
                file_put_contents($dir . $logFilename, self::formatError($error, $type), FILE_APPEND);
            }
        } else {
            throw new \LogicException("La class n'a pas été initialisée. " . self::$documentation);
        }
    }
    
    
    
    /**
     * @return string Le nom de la class CSS
     */
    public static function getCssClass() {
        return (self::$options["cssClass"]);
    }
    
    
    
    /**
     * @param String $type Le dossier de log recherché
     * @param DateTime $date La date du journal de log recherché
     */
    public static function getRawLog($type = "", DateTime $date = null) {
        if (empty($type) || null === $type) {
            $type = (null === $type) ? ("null") : ("\"\"");
            throw new \InvalidArgumentException("Le type doit être renseigné, type: " . $type);
        }
        if (null === $date) {
            throw new \InvalidArgumentException("La date est nulle");
        }
        
        $subDir = self::$options["logDir"] . "/" . $type;
        if (is_dir($subDir)) {
            if (file_exists($subDir . "/" . $date->format(self::$options["formatDateFilename"]))) {
                if ($log = file_get_contents($filename)) {
                    return $log;
                } else {
                    throw new \RuntimeException("Le fichier n'a pas été lu correctement");
                }
            } else {
                throw new \RuntimeException("Le fichier spécifié n'existe pas");
            }
        } else {
            throw new \RuntimeException("Le dossier spécifié n'existe pas");
        }
    }
    
    
    /**
     * @
     * @param Exception $ex Exception capturée
     * @param string $type Le type d'erreur (Utilisé pour créer des sous-répertoires pour catégoriser les logs)
     * @return string La chaîne de caractères formattée du log
     */
    private static function formatError(Exception $ex, $type = "unknown", $additionalParams = null) {
        $moreDetails = method_exists($ex, "getMoreDetails");
        $err =
            self::START_DELIMITER . PHP_EOL 
            . "DATE:"       . new MyDateTime() . PHP_EOL
            . "HOST:"       . $_SERVER["HTTP_HOST"] . PHP_EOL
            . "TYPE:"       . $type . PHP_EOL
            . "FILE:"       . $ex->getFile() .":". $ex->getLine() ."-". $ex->getCode() . PHP_EOL
            . "MSG:"        . $ex->getMessage() . PHP_EOL;
            if ($moreDetails) {
                $err .= "MOREDETAILS:<pre>" . var_dump($ex->getMoreDetails) . "</pre>" . PHP_EOL;
            }
            if (!empty($additionalParams)) {
                $err .= "ADDITIONALPARAMETERS:<pre>" . var_dump($additionalParams) . "</pre>" . PHP_EOL;
            }

        return $err . self::END_DELIMITER . PHP_EOL;
    }
    
    /**
     * @return array Tableau des logs traités et formattés
     */
    public function parseRawLog($raw = "") {
        /* TODO: return object
         * ->DATE
         * ->HOST
         * ->TYPE
         * ->FILE
         * ->MSG
         * ->MOREDETAILS
         * ->MOREPARAMS
        */
        if ($raw != "") {
            $logs = [];
            preg_match("/(" . self::START_DELIMITER . ")(.*\n*)*(" . self::END_DELIMITER . ")/g", $raw, $logs);
        }
    }
    
    /* ===== PRIVATE METHODS ===== */
    
    /**
     * @param String $str Retire les slashs de fin
     * @return String La chaîne nettoyée
     */
    private static function removeTrailingSlash($str = "") {
        if (substr($str, -1)) {
            $str = substr($str, 0, -1);
        }
        return $str;
    }
}