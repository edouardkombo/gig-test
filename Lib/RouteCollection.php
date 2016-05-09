<?php
/**
 * Created by Edouard Kombo.
 * @Author: Edouard Kombo
 * Date: 20/04/2016
 * Time: 10:42
 */

namespace App\Lib;

use PHPRouter\RouteCollection as PHPRouterRouteCollection;

/**
 * Class Route
 *
 * Inherit from PHPRouter\Route to inject Dependency Injection container (ServiceContainer) inside controller objects
 *
 * @package App\Lib
 */
class RouteCollection extends PHPRouterRouteCollection
{
    /**
     * Attach a Route to the collection.
     *
     * @param Route $attachObject
     */
    public function attachRoute(Route $attachObject)
    {
        parent::attach($attachObject, null);
    }
    /**
     * Fetch all routes stored on this collection of routes and return it.
     *
     * @return Route[]
     */
    public function all()
    {
        $temp = array();
        foreach ($this as $route) {
            $temp[] = $route;
        }
        return $temp;
    }
}