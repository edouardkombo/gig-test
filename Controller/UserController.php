<?php

/**
 * @author: Edouard Kombo
 */

namespace App\Controller;

use \Doctrine\ORM\EntityManager;
use \App\Entity\User as User;
use \App\Entity\Item as Item;
use \App\Entity\ItemType as ItemType;
use \App\Lib\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use JasonGrimes\Paginator;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController {

    protected $dependencyInjector;

    /**
     * UserController constructor.
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->dependencyInjector = $serviceContainer;
    }

    /**
     * Add an item to the current user
     *
     * add Action
     */
    public function addAction()
    {
        //Call necessary dependencies
        $request        = Request::createFromGlobals();

        //Retrieve entity manager
        $em             = $this->dependencyInjector->get('entity.manager');

        //Retrieve current user
        $user           = $em->getRepository('\App\Entity\User')->findOneById('1');

        //Retrieve post values
        $number         = $request->request->get('number');
        $type           = $request->request->get('type');

        //Check parameters
        if ((isset($number) && is_integer($number)) && ((isset($type) && is_integer($type)))) {

            $itemType       = $em->getRepository('\App\Entity\ItemType')->findOneById($type);

            //If item type exists we continue
            if ($itemType instanceof \App\Entity\ItemType) {

                $item           = new Item();
                $item->setNumber($number);
                $item->setType($itemType);

                $user->addItem($item);

                //Save the user
                $em->persist($user);

                if (is_null($em->flush())) {
                    $datas = array('message' => 'Item successfully added to user !', 'status' => 'success');
                } else {
                    $datas = array('message' => 'Something went wrong, please try again later !', 'status' => 'error');
                }

            } else {
                //Otherwhise we show error
                $datas = array('message' => 'Item not found', 'status' => 'error');
            }
        } else {
            //Otherwhise we show error
            $datas = array('message' => 'Wrong parameters sent !', 'status' => 'error');
        }

        $response = new JsonResponse();
        $response->setData($datas);
        return $response->send();
    }

    /**
     * Delete an item from the current user
     *
     * delete Action
     */
    public function deleteAction()
    {
        //Call necessary dependencies
        $request        = Request::createFromGlobals();

        //Retrieve entity manager
        $em             = $this->dependencyInjector->get('entity.manager');

        //Retrieve current user
        $user           = $em->getRepository('\App\Entity\User')->findOneById('1');

        //Retrieve post values
        $number         = (integer) $request->request->get('number');
        $type           = (integer) $request->request->get('type');

        //Check parameters
        if ((isset($number) && is_integer($number)) && ((isset($type) && is_integer($type)))) {

            $itemType       = $em->getRepository('\App\Entity\ItemType')->findOneById($type);

            //If item type exists we continue
            if ($itemType instanceof \App\Entity\ItemType) {

                //if item exists
                $item       = $em->getRepository('\App\Entity\Item')->findOneBy(array(
                    'number' => $number,
                    'type' => $itemType
                ));

                if ($item instanceof \App\Entity\Item) {

                    $user->removeItem($item);

                    //Save the user
                    $em->persist($user);

                    if (is_null($em->flush())) {
                        $datas = array('message' => 'Item successfully removed from user !', 'status' => 'success');
                    } else {
                        $datas = array('message' => 'Something went wrong, please try again later !', 'status' => 'error');
                    }

                } else {

                    $datas = array('message' => 'Item not found', 'status' => 'error');
                }

            } else {
                //Otherwhise we show error
                $datas = array('message' => 'Item type not found', 'status' => 'error');
            }
        } else {
            //Otherwhise we show error
            $datas = array('message' => 'Wrong parameters sent !', 'status' => 'error');
        }

        $response = new JsonResponse();
        $response->setData($datas);
        return $response->send();
    }
}