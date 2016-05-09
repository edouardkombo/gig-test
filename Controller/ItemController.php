<?php

/**
 * @author: Edouard Kombo
 */

namespace App\Controller;

use \Doctrine\ORM\EntityManager;
use \App\Entity\Item as Item;
use \App\Entity\ItemType as ItemType;
use \App\Lib\Helper as Helper;
use \App\Lib\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ItemController
 * @package App\Controller
 */
class ItemController {

    protected $dependencyInjector;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->dependencyInjector = $serviceContainer;
    }


    /**
     * Add an item to the database
     *
     * add Action
     */
    public function addAction()
    {
        //Call necessary dependencies
        $request        = Request::createFromGlobals();

        //Retrieve dependencies
        $em             = $this->dependencyInjector->get('entity.manager');

        //Prepare the json response object
        $response       = new JsonResponse();

        //Retrieve posted values
        $name             = (string) $request->request->get('name');


        if (isset($name) && !empty($name)) {

            //Prepare itemType and item objects
            $itemType         = new ItemType();

            //Create slug from name string And search for slugs in itemType table
            $slug             = Helper::slugify($name);

            $itemTypeObject   = $em->getRepository('\App\Entity\ItemType')->findOneBy(array('slug' => $slug));

            //If an item type is found, we don't insert
            if ($itemTypeObject instanceof \App\Entity\ItemType) {

                $datas = array('message' => 'An item with this name already exists !', 'status' => 'error');

            } else {
                //otherwhise we create the item name
                $itemType->setName($name);
                $em->persist($itemType);

                //Save
                if (is_null($em->flush())) {
                    $datas = array('message' => 'Item successfully created !', 'status' => 'success');
                } else {
                    $datas = array('message' => 'Something went wrong, please try again later !', 'status' => 'error');
                }
            }
        } else {
            $datas = array('message' => 'No post arguments received !', 'status' => 'error');
        }


        $response->setData($datas);
        return $response->send();
    }
}