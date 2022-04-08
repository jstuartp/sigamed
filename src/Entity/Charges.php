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
 * @ORM\Table(name="tek_charges")
 * @ORM\Entity()
 */
class Charges
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=360, nullable = true)
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $detailin;

    /**
     * @ORM\Column(type="string", length=360, nullable = true)
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $detailout;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

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
     * Set detailin
     *
     * @param string $detailin
     */
    public function setDetailin($detailin)
    {
        $this->detailin = $detailin;
    }

    /**
     * Get detailin
     *
     * @return string
     */
    public function getDetailin()
    {
        return $this->detailin;
    }

    /**
     * Set detailout
     *
     * @param string $detailout
     */
    public function setDetailout($detailout)
    {
        $this->detailout = $detailout;
    }

    /**
     * Get detailout
     *
     * @return string
     */
    public function getDetailout()
    {
        return $this->detailout;
    }
}