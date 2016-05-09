<?php
/**
 * Created by Edouard Kombo.
 * @Author: Edouard Kombo
 * Date: 20/04/2016
 * Time: 10:42
 */

namespace App\Lib;

use PHPRouter\RouteCollection;
use PHPRouter\Router as PHPRouterRouter;

/**
 * @Co-Author: Edouard Kombo
 * Class Route
 *
 * What I've done here:
 * 1. Inject here the serviceContainer object and manipulate it inside Route Class
 * 2. Throw exception when requested method or route has not been found !
 * 3. Inside Match function replaced $matches[$key + 1] by $matches[$key] to be able to retrieve arguments
 *
 * @package App\Lib
 */
class Router extends PHPRouterRouter
{
    /**
     * RouteCollection that holds all Route objects
     *
     * @var RouteCollection
     */
    private $routes = array();

    /**
     * Array to store named routes in, used for reverse routing.
     * @var array
     */
    private $namedRoutes = array();

    /**
     * The base REQUEST_URI. Gets prepended to all route url's.
     * @var string
     */
    private $basePath = '';

    /**
     * @Author: Edouard Kombo
     * ServiceContainer object
     * @var object
     */
    public $serviceContainer = '';

    /**
     * @param RouteCollection $collection
     */
    public function __construct(RouteCollection $collection)
    {
        $this->routes = $collection;
        foreach ($this->routes->all() as $route) {
            $name = $route->getName();
            if (null !== $name) {
                $this->namedRoutes[$name] = $route;
            }
        }
    }

    /**
     * @Author: Edouard Kombo
     *
     * Inject serviceContainer object to be able to instantiate each Controller with this instance service
     *
     * @param $serviceContainer object
     */
    public function injectServiceContainer($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * Set the base _url - gets prepended to all route _url's.
     * @param $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * Matches the current request against mapped routes
     */
    public function matchCurrentRequest()
    {
        $requestMethod = (
            isset($_POST['_method'])
            && ($_method = strtoupper($_POST['_method']))
            && in_array($_method, array('PUT', 'DELETE'))
        ) ? $_method : $_SERVER['REQUEST_METHOD'];

        $requestUrl = $_SERVER['REQUEST_URI'];

        // strip GET variables from URL
        if (($pos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $pos);
        }

        return $this->match($requestUrl, $requestMethod);
    }

    /**
     * @co-author: Edoaurd Kombo
     *
     * Match given request _url and request method and see if a route has been defined for it
     * If so, return route's target
     * If called multiple times
     *
     * @param string $requestUrl
     * @param string $requestMethod
     *
     * @return bool
     * @throws \Exception
     */
    public function match($requestUrl, $requestMethod = 'GET')
    {
        foreach ($this->routes->all() as $routes) {
            // compare server request method with route's allowed http methods
            if (!in_array($requestMethod, (array)$routes->getMethods())) {
                continue;
            }

            $currentDir = dirname($_SERVER['SCRIPT_NAME']);
            if ($currentDir != '/') {
                $requestUrl = str_replace($currentDir, '', $requestUrl);
            }

            // check if request _url matches route regex. if not, return false.

            if (!preg_match("@^" . $this->basePath . $routes->getRegex() . "*$@i", $requestUrl, $matches)) {
                continue;
            }

            $matchedText = array_shift($matches);

            $params = array();

            if (preg_match_all("/:([\w-%]+)/", $routes->getUrl(), $argument_keys)) {
                // grab array with matches
                $argument_keys = $argument_keys[1];

                // check arguments number
                if(count($argument_keys) != count($matches)) {
                    continue;
                }

                // loop trough parameter names, store matching value in $params array
                foreach ($argument_keys as $key => $name) {
                    if (isset($matches[$key])) {
                        $params[$name] = $matches[$key];
                    }
                }

            }

            $routes->setParameters($params);

            /**
             * @Author: Edouard Kombo
             *
             * Pass here the object to Route Class
             */
            $routes->serviceContainer = $this->serviceContainer;

            $routes->dispatch();

            return $routes;
        }

        if (php_sapi_name() == "cli") {
            return false;
        } else {
            throw new \Exception('Invalid method or route requested !');
        }
    }

    /**
     * Reverse route a named route
     *
     * @param $routeName
     * @param array $params Optional array of parameters to use in URL
     *
     * @throws Exception
     *
     * @return string The url to the route
     */
    public function generate($routeName, array $params = array())
    {
        // Check if route exists
        if (!isset($this->namedRoutes[$routeName])) {
            throw new Exception("No route with the name $routeName has been found.");
        }

        /** @var \PHPRouter\Route $route */
        $route = $this->namedRoutes[$routeName];
        $url = $route->getUrl();

        // replace route url with given parameters
        if ($params && preg_match_all("/:(\w+)/", $url, $param_keys)) {
            // grab array with matches
            $param_keys = $param_keys[1];

            // loop trough parameter names, store matching value in $params array
            foreach ($param_keys as $key) {
                if (isset($params[$key])) {
                    $url = preg_replace("/:(\w+)/", $params[$key], $url, 1);
                }
            }
        }

        return $url;
    }

    /**
     * Create routes by array, and return a Router object
     *
     * @param array $config provide by Config::loadFromFile()
     * @return Router
     */
    public static function parseConfig(array $config)
    {
        $collection = new RouteCollection();
        foreach ($config['routes'] as $name => $route) {
            $collection->attachRoute(new Route($route[0], array(
                '_controller' => str_replace('.', '::', $route[1]),
                'methods' => $route[2],
                'name' => $name
            )));
        }

        $router = new Router($collection);
        if (isset($config['base_path'])) {
            $router->setBasePath($config['base_path']);
        }

        return $router;
    }
}