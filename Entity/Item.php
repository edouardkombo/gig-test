<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="item")
 **/
class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \App\Entity\User $user
     *
     * @ORM\ManyToMany(targetEntity="\App\Entity\User", inversedBy="items")
     * @ORM\JoinTable(name="users_items",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id")}
     * )
     */
    protected $users;


    /**
     * Number of items
     *
     * @ORM\Column(type="integer", name="number")
     */
    protected $number;

    /**
     * @ORM\OneToOne(targetEntity="\App\Entity\ItemType", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $type;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set number
     *
     * @param Integer $number
     *
     * @return Item
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get Integer
     *
     * @return Integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set type
     *
     * @param \App\Entity\ItemType $type
     *
     * @return Item
     */
    public function setType(\App\Entity\ItemType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \App\Entity\ItemType
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param User $user
     */
    public function addUser(\App\Entity\User $user)
    {
        $this->users[] = $user;
    }

    /**
     * @param User $user
     */
    public function removeUser(\App\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Set user
     *
     * @param \App\Entity\User $user
     *
     * @return Item
     */
    public function setUser(\App\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}