<?php

/**
 * FIRST! Create basic user from CLI and then delete this file
 *
 * Format: php cli-create-user.php email
 *
 * example: php cli-create-user.php edouard.kombo@gmail.com
 */

define('__ROOT_PATH__', realpath(dirname(__FILE__) . '/' ));
require_once "vendor/autoload.php"; //Autoload composer dependencies
require_once "config/ProjectAutoloaderConfig.php"; //Autoload project classes (Simplier than yours before)
require_once "config/Environment.php"; //Also delete cache directory when in dev mode
require_once "config/DoctrineConfig.php"; //Instantiate Doctrine object


if ((php_sapi_name() == "cli") && is_array($argv) && count($argv) >= 1) {

    $email = $argv[1];

    $user = new \App\Entity\User();
    $user->setEmail($email);
    $entityManager->persist($user);
    $entityManager->flush();

    echo "Test user successfully Created, you can now log in !"."\r\n";


} else {
    echo "Test user not created, please specify cli arguments !"."\r\n";
}