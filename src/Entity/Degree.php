<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tek_degree")
 * @ORM\Entity()
 */
class Degree
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
     * @ORM\Column(name="place", type="string", length=160)
     */
    private $place;


    /**
     * @ORM\Column(name="year", type="string", length=160)
     */
    private $year;


    /**
     * @ORM\Column(name="country", type="string", length=160)
     */
    private $country;

    /**
     * @ORM\Column(name="eedition", type="integer")
     */
    private $eedition;

    /**
     * @ORM\Column(name="inprogress", type="integer")
     */
    private $inprogress;

    /**
     * @ORM\Column(name="equated", type="integer")
     */
    private $equated;

    /**
     * @ORM\Column(name="equatedtx", type="string", length=600)
     */
    private $equatedtx;


    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
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
     * Set year
     *
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
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

    /**
     * Set eedition
     *
     * @param integer $eedition
     */
    public function setEedition($eedition)
    {
        $this->eedition = $eedition;
    }

    /**
     * Get eedition
     *
     * @return integer
     */
    public function getEedition()
    {
        return $this->eedition;
    }

    /**
     * Set inprogress
     *
     * @param integer $inprogress
     */
    public function setInprogress($inprogress)
    {
        $this->inprogress = $inprogress;
    }

    /**
     * Get inprogress
     *
     * @return integer
     */
    public function getInprogress()
    {
        return $this->inprogress;
    }

    /**
     * Set equated
     *
     * @param integer $equated
     */
    public function setEquated($equated)
    {
        $this->equated = $equated;
    }

    /**
     * Get equated
     *
     * @return integer
     */
    public function getEquated()
    {
        return $this->equated;
    }

    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set equatedtx
     *
     * @param string $equatedtx
     */
    public function setEquatedtx($equatedtx)
    {
        $this->equatedtx = $equatedtx;
    }

    /**
     * Get equatedtx
     *
     * @return string
     */
    public function getEquatedtx()
    {
        return $this->equatedtx;
    }
}
?>
