<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tek_aexperience")
 * @ORM\Entity()
 */
class Aexperience
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="subject", type="string", length=600)
     */
    private $subject;

    /**
     * @ORM\Column(name="place", type="string", length=160)
     */
    private $place;


    /**
     * @ORM\Column(name="year", type="string", length=160)
     */
    private $year;

    /**
     * @ORM\Column(name="performance", type="string", length=15, nullable=true )
     */
    private $performance;

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
     * Set subject
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
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
     * Set performance
     *
     * @param string $performance
     */
    public function setPerformance($performance)
    {
        $this->performance = $performance;
    }

    /**
     * Get performance
     *
     * @return string
     */
    public function getPerformance()
    {
        return $this->performance;
    }
}
?>
