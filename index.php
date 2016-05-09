<?php

/**
 * It is a very good practice to deal with namespaces inside a project
 * So, I decided to add a namespace at the beginning of the project
 */
namespace App;

/**
 *
 * @Author: Edouard Kombo
 **/

// start the session
session_start();

define('__ROOT_PATH__', realpath(dirname(__FILE__) . '/' ));

/**
 * @Author: Edouard Kombo
 *
 * NOTICE: Everything below has been developed or implemented by me
 */

    require_once "vendor/autoload.php"; //Autoload composer dependencies
    require_once "config/ProjectAutoloaderConfig.php"; //Autoload project classes (Simplier than yours before)

    /**
     * Here, I have included a specific php debugger (Tracy Nette) to show php errors in a beautiful way
     * it is easy for debugging
     */
    require_once "config/Environment.php"; //Also delete cache directory when in dev mode

    use App\Lib\ServiceContainer; //Simple dependency injection container I've written (very basic)


    /**
     * Inherit from PHPRouter to add our customs modification (instantiate each controller with dependency injection container)
     */
    use App\Lib\RouteCollection;
    use PHPRouter\Config;
    use App\Lib\Router;
    use App\Lib\Route;

    use Symfony\Component\HttpFoundation\Request;

    require_once "config/DoctrineConfig.php"; //Instantiate Doctrine object

    /**
     * Instantiate Twig template engine object
     * Because it is not a good id to insert php variables into view, it is a MVC pattern breaking rules,
     * we'll proceed like this
     *
     * We transmit session variable to twig to be able to call easier certain values
     */
    $loader = new \Twig_Loader_Filesystem(__DIR__ . '/view');
    $twig = new \Twig_Environment($loader, array(
        'cache' => __DIR__ . '/cache',
    ));
    //Create global Session value for twig, to call easily User datas later, and also Role permissions
    $twig->addGlobal('session', $passport->datas);

    /**
     * Inject twig instance into dependency injection container
     * Inject "Entity Manager" instance into dependency injection container from Doctrine (see config/DoctrineConfig.php)
     */
    $serviceContainer = new ServiceContainer();
    $serviceContainer->register('template.engine', $twig);
    $serviceContainer->register('entity.manager', $entityManager);

    /**
     * Load routes from config file in yaml
     * Pass the dependency container object to controller class construction
     * Match current Request and call specified controller and action
     *
     * HERE, we use our inheritance from PHPRouter
     */
    $config = Config::loadFromFile('config/routing.yml');
    $router = \App\Lib\Router::parseConfig($config);
    $router->serviceContainer = $serviceContainer;
    //We don't need this on cli mode as it will trigger php notices due to $_SERVER or $_REQUEST
    if (php_sapi_name() != "cli") {
        $router->matchCurrentRequest();
    }

