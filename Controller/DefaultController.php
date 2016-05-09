<?php

/**
 * @Author: Edoaurd Kombo
 */

namespace App\Controller;


use \App\Lib\ServiceContainer;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController {

    protected $dependencyInjector;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->dependencyInjector = $serviceContainer;
    }

    /**
     * Default method leads to login page
     *
     * If user is authenticated, redirect to user page
     *
     * index Action
     */
    public function indexAction()
    {
        $passport       = $this->dependencyInjector->get('passport.manager');

        //if user is logged in, do not show anymore the login page, instead, redirect to the user page
        if (false === $passport->isAuthenticated()) {
            $twig = $this->dependencyInjector->get('template.engine');
            return $twig->render('/login/login.html.twig');
        } else {
            return new RedirectResponse('/user');
        }
    }
}