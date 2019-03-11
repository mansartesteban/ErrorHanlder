<?php

namespace ErrorHandler;

/**
 * Classe de gestion de log d'erreur.
 * Visitez https://github.com/mansartesteban/ErrorHandler pour consulter la documentation
 */
class ErrorHandler {
    
    private static $created = false;
    private static $options = [];
    private static $documentation = "Pour plus d'informations, veuillez consulter la documentation disponible sur : https://github.com/mansartesteban/ErrorHandler";
    
    /* ===== PUBLIC METHODS ===== */

    /**
     * @param array $options Tableau de paramètres
     */
    public static function init(array $options = []) {
        self::$options = [
            "logDir" => "/log",
            "formats" => [
                "dateFilename" => "Y-m-d", // Format de date pour le nom du fichier
                "dateLog" => "Y-m-d H:i:s.u" // Format de date pour le log en lui-même
            ],
            "logSeparator" => ">_______________<",
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
     * @param \Exception $error L'exception
     * @param mixed $additionalParams
     * @throws \Exception
     */
    public static function log($type = "", \Exception $error = null, $additionalParams = null) {
        if (self::$created) {
            if (null === $type || empty($type)) {
                $type = "unknown";
            }
            if (null === $error) {
                throw new \RuntimeException("L'erreur passée en arguments est nulle");
            }
            $now = new \DateTime();
            $logFilename = $now->format(self::$options["formats"]["dateFilename"]) . self::$options["fileExtension"];
            $logDir = self::$options["logDir"];
            if (!is_dir($logDir)) {
                if (!mkdir($logDir)) {
                    throw new \RuntimeException("Impossible de créer le répertoire de log, veuillez vérifier si vous avez les droits d'écriture suffisants");
                }
            }
            $dir = $logDir . "/" . strtolower($type) . "/";
            if (!is_dir($dir)) {
                if (!mkdir($dir)) {
                    throw new \RuntimeException("Impossible de créer le sous-répertoire de log \"" .strtolower($type) .  "\", veuillez vérifier si vous avez les droits d'écriture suffisants");
                }
            }
            file_put_contents($dir . $logFilename, self::formatError($error, $type, $now, $additionalParams), FILE_APPEND);

        } else {
            throw new \LogicException("La class n'a pas été initialisée. " . self::$documentation);
        }
    }


    /**
     * @param String $type Le dossier de log recherché
     * @param \DateTime $date La date du journal de log recherché
     * @return string|false Retourne le contenu du fichier trouvé sous forme de chaîne de caractères, false si une erreur survient
     * @throws \Exception
     */
    public static function getRawLog($type = "", \DateTime $date = null) {
        if (empty($type) || null === $type) {
            $type = (null === $type) ? ("null") : ("\"\"");
            throw new \InvalidArgumentException("Le type doit être renseigné, type: " . $type);
        }
        if (null === $date) {
            $date = new \DateTime();
        }
        
        $subDir = self::$options["logDir"] . "/" . $type;
        if (is_dir($subDir)) {
            $filename = $subDir . "/" . $date->format(self::$options["formats"]["dateFilename"]) . self::$options["fileExtension"];
            if (file_exists($filename)) {
                if ($log = file_get_contents($filename)) {
                    return $log;
                } else {
                    throw new \RuntimeException("Le fichier n'a pas été lu correctement");
                }
            } else {
                return false; // Il n'y a pas d'erreur, juste le fichier n'existe pas;
            }
        } else {
            throw new \RuntimeException("Le dossier spécifié n'existe pas");
        }
        return false;
    }


    /**
     * @param string $raw Le format brut reçu de ErrorHandler::getRawLog()
     * @return array|false Tableau des logs traités et formattés
     * @throws \Exception
     */
    public static function parseRawLog($raw = "") {
        if ($raw != "") {
            $logs = explode(self::$options["logSeparator"], $raw);
            array_pop($logs);
            $ret = [];
            foreach ($logs as $log) {
                $matches = [];
                preg_match_all("/([A-Z]+):(.*)\n/", $log, $matches);
                $ret[] = new EHError(array_combine($matches[1], $matches[2]));
            }

            return ($ret);
        }
        return false;
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

    /**
     * @param \Exception $ex Exception capturée
     * @param string $type Le type d'erreur (Utilisé pour créer des sous-répertoires pour catégoriser les logs)
     * @param \DateTime|null $now La date de création du fichier de log (utilisé pour le nom)
     * @param mixed $additionalParams Paramètres supplémentaires que vous souhaitez y ajouter
     * @return string La chaîne de caractères formattée du log
     */
    private static function formatError(\Exception $ex, $type = "unknown", \DateTime $now = null, $additionalParams = null) {
        $moreDetails = method_exists($ex, "getMoreDetails");
        $err =
            "DATE:"       . $now->format(self::$options["formats"]["dateLog"]) . PHP_EOL
            . "CODE:"       . $ex->getCode() . PHP_EOL
            . "HOST:"       . $_SERVER["HTTP_HOST"] . PHP_EOL
            . "TYPE:"       . $type . PHP_EOL
            . "FILE:"       . $ex->getFile() .":". $ex->getLine() . PHP_EOL
            . "MSG:"        . $ex->getMessage() . PHP_EOL;
        if ($moreDetails) {
            $err .= "MOREDETAILS:<pre>" . var_dump($ex->getMoreDetails) . "</pre>" . PHP_EOL;
        }
        if (!empty($additionalParams)) {
            $err .= "ADDITIONALPARAMETERS:<pre>" . var_dump($additionalParams) . "</pre>" . PHP_EOL;
        }

        return $err . self::$options["logSeparator"] . PHP_EOL;
    }
}