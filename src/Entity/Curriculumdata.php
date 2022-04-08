<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tek_curriculumdata")
 * @ORM\Entity()
 */
class Curriculumdata
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="pension", type="string", length=120)
     */
    private $pension;

    /**
     * @ORM\Column(name="birthday", type="string", length=45)
     */
    private $birthday;

    /**
     * @ORM\Column(name="email", type="string", length=60)
     */
    private $email;

    /**
     * @ORM\Column(name="phone", type="string", length=30)
     */
    private $phone;

    /**
     * @ORM\Column(name="gender", type="integer")
     */
    private $gender;

    /**
     * @ORM\Column(name="degree", type="integer")
     */
    private $degree;

    /**
     * @ORM\Column(name="regimen", type="integer")
     */
    private $regimen;

    /**
     * @ORM\Column(name="regimentx", type="string", length=45)
     */
    private $regimentx;


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
     * Set pension
     *
     * @param string $pension
     */
    public function setPension($pension)
    {
        $this->pension = $pension;
    }

    /**
     * Get pension
     *
     * @return string
     */
    public function getPension()
    {
        return $this->pension;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * Get birthday
     *
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
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
     * Set degree
     *
     * @param integer $degree
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;
    }

    /**
     * Get degree
     *
     * @return integer
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set regimen
     *
     * @param integer $regimen
     */
    public function setRegimen($regimen)
    {
        $this->regimen = $regimen;
    }

    /**
     * Get regimen
     *
     * @return integer
     */
    public function getRegimen()
    {
        return $this->regimen;
    }

    /**
     * Set regimentx
     *
     * @param string $regimentx
     */
    public function setRegimentx($regimentx)
    {
        $this->regimentx = $regimentx;
    }

    /**
     * Get regimentx
     *
     * @return string
     */
    public function getRegimentx()
    {
        return $this->regimentx;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

}
?>
