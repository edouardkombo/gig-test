<?php

use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
use \Doctrine\Common\Annotations\AnnotationReader;

use \Symfony\Component\Yaml\Yaml as Yaml;

//Search database config inside yaml file
$dbConfig = Yaml::parse(file_get_contents(__ROOT_PATH__ . '/config/database.yml'));

//Path to Entity directory
$entityPath = array( __ROOT_PATH__ ."/Entity");
$isDevMode = ($environment === 'dev') ? true : false;

// the connection configuration
$dbParams = array(
    'driver'   => $dbConfig['database']['driver'],
    'user'     => $dbConfig['database']['user'],
    'password' => $dbConfig['database']['password'],
    'dbname'   => $dbConfig['database']['dbname'],
);

$cache = new \Doctrine\Common\Cache\ArrayCache();
$reader = new AnnotationReader();
$driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, $entityPath);

$config = Setup::createAnnotationMetadataConfiguration($entityPath, $isDevMode);
$config->setMetadataCacheImpl( $cache );
$config->setQueryCacheImpl( $cache );
$config->setMetadataDriverImpl( $driver );

//This variable will help us calling the entity manager to be able to make Doctrine requests
$entityManager = EntityManager::create($dbParams, $config);

//-- This I had to add to support the Mysql enum type.
$platform = $entityManager->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('enum', 'string');