<?php

use \Symfony\Component\Yaml\Yaml as Yaml;
use \Tracy\Debugger; //debugger tool

//Search general config from yml file
$environmentConfig = Yaml::parse(file_get_contents(__ROOT_PATH__ . '/config/config.yml'));

$environment = $environmentConfig['site']['env'];

//If environment is dev, show all php errors and delete cache directory
//If not, do not show any error
if ($environment === 'dev') {

    ini_set('ERROR_REPORTING', E_ALL | E_STRICT);

    //If it is not cli mode (to avoid errors due to error handlers
    if (php_sapi_name() != "cli") {
        Debugger::$maxDepth = 2; // default: 3
        Debugger::$maxLen = 50; // default: 150
        Debugger::$strictMode = TRUE;
        Debugger::$logSeverity = E_NOTICE | E_WARNING;
        Debugger::$showLocation = TRUE; // Shows all additional location information
        Debugger::enable(Debugger::DEVELOPMENT, __ROOT_PATH__ . '/logs');

        $cacheDirectory = __ROOT_PATH__ . "/cache";

        //Delete only folders inside cache directory, not the folder himself
        $it = new RecursiveDirectoryIterator($cacheDirectory, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
    }

} else {
    Debugger::PRODUCTION;
}