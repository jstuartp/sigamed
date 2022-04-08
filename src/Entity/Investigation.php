<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tek_investigation")
 * @ORM\Entity()
 */
class Investigation
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=600)
     */
    private $name;

    /**
     * @ORM\Column(name="place", type="string", length=120)
     */
    private $place;


    /**
     * @ORM\Column(name="validity", type="string", length=120, nullable=true)
     */
    private $validity;

    /**
     * @ORM\Column(name="type", type="string", length=60, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(name="status", type="integer", nullable = true)
     */
    private $status;

    /**
     * @ORM\Column(name="descriptors", type="string", length=600, nullable=true)
     */
    private $descriptors;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;


    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set place
     *
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set validity
     *
     * @param string $validity
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;
    }

    /**
     * Get validity
     *
     * @return string
     */
    public function getValidity()
    {
        return $this->validity;
    }

    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function geType()
    {
        return $this->type;
    }


    /**
     * Set descriptors
     *
     * @param string $descriptors
     */
    public function setDescriptors($descriptors)
    {
        $this->descriptors = $descriptors;
    }

    /**
     * Get descriptors
     *
     * @return string
     */
    public function getDescriptors()
    {
        return $this->descriptors;
    }


    /**
     * Set user
     *
     * @param \App\Entity\User $user
     */
    public function setUser(\App\Entity\User $user)
    {
        $this->user = $user;
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
?>
