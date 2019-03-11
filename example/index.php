<?php
/**
 * Created by PhpStorm.
 * User: Esteban
 * Date: 08/03/2019
 * Time: 22:49
 */

namespace example;

use ErrorHandler\ErrorHandler;

require_once(__DIR__ . "/../vendor/autoload.php");


$options = [
    "logDir" => __DIR__ . "/log",
    "formats" => [
        "dateFilename" => "Y-m-d",
        "dateLog" => "Y-m-d H:i:s.u"
    ],
    "fileExtension" => ".log"
];
ErrorHandler::init($options);