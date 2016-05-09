<?php

/**
 * @Author: Edouard Kombo
 *
 * @param string $className
 */
function projectAutoloader($className) {

    $vars1 = (array) ['App', '\\'];
    $vars2 = (array) ['', '/'];
    $newClassName = (string) __ROOT_PATH__ . str_replace($vars1, $vars2, $className) . '.php';

    try {
        if(file_exists($newClassName)) {
            require_once($newClassName);
        } else {
            throw new Exception("Class $className not found !");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

spl_autoload_register('projectAutoloader');