<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 *
 * @ORM\Table(name="tek_assigned_commissions")
 * @ORM\Entity()
 */
class AssignedCommission
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(name="weight", type="string", length=30, nullable=true)
     */
    private $weight;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $teacher;


    /**
     * @ManyToOne(targetEntity="Period")
     * @JoinColumn(name="period_id", referencedColumnName="id")
     */
    private $period;

    /**
     * @ManyToOne(targetEntity="Commission")
     * @JoinColumn(name="commission_id", referencedColumnName="id")
     */
    private $commission;

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
     * Set user
     *
     * @param \App\Entity\User $user
     */
    public function setUser(\App\Entity\User $user)
    {
        $this->teacher = $user;
    }

    /**
     * Get user
     *
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->teacher;
    }


    /**
     * Set period
     *
     * @param \App\Entity\Period $period
     */
    public function setPeriod(\App\Entity\Period $period)
    {
        $this->period = $period;
    }

    /**
     * Get period
     *
     * @return \App\Entity\Period
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set commission
     *
     * @param \App\Entity\Commission $commission
     */
    public function setCommission(\App\Entity\Commission $commission)
    {
        $this->commission = $commission;
    }

    /**
     * Get commission
     *
     * @return \App\Entity\Commission
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * Set type
     *
     * @param integer $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set weight
     *
     * @param string $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }



}