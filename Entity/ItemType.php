<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;
use \App\Lib\Helper as Helper;

/**
 * @ORM\Entity
 * @ORM\Table(name="item_type")
 **/
class ItemType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;


    /**
     * Name of the item
     *
     * @ORM\Column(type="string", name="name")
     */
    protected $name;

    /**
     * Slug name of the item to avoid repition
     *
     * @ORM\Column(type="string", name="slug", unique=true)
     */
    protected $slug;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set name
     *
     * @param Integer $name
     *
     * @return ItemType
     */
    public function setName($name)
    {
        $this->name = $name;

        $slug = Helper::slugify($this->name);
        $this->setSlug($slug);

        return $this;
    }

    /**
     * Get name
     *
     * @return Integer
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param Integer $slug
     *
     * @return ItemType
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return Integer
     */
    public function getSlug()
    {
        return $this->slug;
    }
}