<?php
/**
 * Created by Edouard Kombo.
 * @Author: Edouard Kombo
 * Date: 20/04/2016
 * Time: 10:42
 */

namespace App\Lib;

/**
 * Class ServiceContainer
 * Dependency injection container, register all necessary objects to be injected inside controllers
 * @package App\Lib
 */
class ServiceContainer
{

    /**
     * Register an object
     *
     * @param $name string
     * @param $object Object
     */
    public function register($name, $object)
    {
        $this->__set($name, $object);
    }


    /**
     * Get specified object
     *
     * @param $name string
     * @return mixed
     */
    public function get($name)
    {
        return $this->__get($name);
    }

    /**
     * @param $property string
     * @param $value Object
     */
    public function __set($property, $value) {
        $this->$property = $value;
    }

    /**
     * @param $property string
     * @return mixed
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            return false;
        }
    }

}