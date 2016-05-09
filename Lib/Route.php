<?php
/**
 * Created by Edouard Kombo.
 * @Author: Edouard Kombo
 * Date: 20/04/2016
 * Time: 10:42
 */

namespace App\Lib;

use PHPRouter\Route as PHPRouterRoute;

/**
 * Class Route
 *
 * Inherit from PHPRouter\Route to inject Dependency Injection container (ServiceContainer) inside controller objects
 * Method modified => "Dispatch"
 *
 * @package App\Lib
 */
class Route extends PHPRouterRoute
{

    /**
     * URL of this Route
     * @var string
     */
    private $url;

    /**
     * Accepted HTTP methods for this route.
     * @var string[]
     */
    private $methods = array('GET', 'POST', 'PUT', 'DELETE');

    /**
     * Target for this route, can be anything.
     * @var mixed
     */
    private $target;

    /**
     * The name of this route, used for reversed routing
     * @var string
     */
    private $name = null;

    /**
     * Custom parameter filters for this route
     * @var array
     */
    private $filters = array();

    /**
     * Array containing parameters passed through request URL
     * @var array
     */
    private $parameters = array();

    /**
     * Set named parameters to target method
     * @example [ [0] => [ ["link_id"] => "12312" ] ]
     * @var bool
     */
    private $parametersByName;

    /**
     * @var array
     */
    private $config;

    /**
     * @var object
     */
    public $serviceContainer;

    /**
     * @param       $resource
     * @param array $config
     */
    public function __construct($resource, array $config)
    {
        $this->url = $resource;
        $this->config = $config;
        $this->methods = isset($config['methods']) ? (array)$config['methods'] : array();
        $this->target = isset($config['target']) ? $config['target'] : null;
        $this->name = isset($config['name']) ? $config['name'] : null;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $url = (string)$url;

        // make sure that the URL is suffixed with a forward slash
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }

        $this->url = $url;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = (string)$name;
    }

    public function setFilters(array $filters, $parametersByName = false)
    {
        $this->filters = $filters;

        if ($parametersByName) {
            $this->parametersByName = true;
        }
    }

    public function getRegex()
    {
        return preg_replace_callback("/(:\w+)/", array(&$this, 'substituteFilter'), $this->url);
    }

    private function substituteFilter($matches)
    {
        if (isset($matches[1]) && isset($this->filters[$matches[1]])) {
            return $this->filters[$matches[1]];
        }

        return "([\w-%]+)";
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @Co-Author: Edouard Kombo
     * Inject dependency injection container in each Controller
     */
    public function dispatch()
    {
        $action = explode('::', $this->config['_controller']);
        $instance = new $action[0]($this->serviceContainer);
        if ($this->parametersByName) {
            $this->parameters = array($this->parameters);
        }

        echo call_user_func_array(array($instance, $action[1]), $this->parameters);
        return true;
    }
}