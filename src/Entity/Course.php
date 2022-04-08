<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\User;
/**
 *
 * @ORM\Table(name="tek_courses")
 * @ORM\Entity()
 * @UniqueEntity("name")
 */
class Course
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 150,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=60, nullable=true))
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $requisit;

    /**
     * @ORM\Column(type="string", length=60, nullable=true))
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $corequisite;

    /**
     * @ORM\Column(type="string", length=2, nullable=true))
     * @Assert\Length(
     *      max = 2,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $credit;

    /**
     * @ORM\Column(type="string", length=60, nullable=true))
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $area;

    /**
     * @ORM\Column(type="string", length=60, nullable=true))
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $schedule;

    /**
     * @ORM\Column(type="string", length=10, nullable=true))
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $groupnumber;

    /**
     * @ORM\Column(type="string", length=60, nullable=true))
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $classroom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $room;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=120, nullable=true))
     * @Assert\Length(
     *      max = 120,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $section;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;


    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->name;
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
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
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
     * Set requisit
     *
     * @param string $requisit
     */
    public function setRequisit($requisit)
    {
        $this->requisit = $requisit;
    }

    /**
     * Get requisit
     *
     * @return string
     */
    public function getRequisit()
    {
        return $this->requisit;
    }

    /**
     * Set corequisite
     *
     * @param string $corequisite
     */
    public function setCorequisite($corequisite)
    {
        $this->corequisite = $corequisite;
    }

    /**
     * Get corequisite
     *
     * @return string
     */
    public function getCorequisite()
    {
        return $this->corequisite;
    }

    /**
     * Set credit
     *
     * @param string $credit
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
    }

    /**
     * Get credit
     *
     * @return string
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set area
     *
     * @param string $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * Get area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set schedule
     *
     * @param string $schedule
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Get schedule
     *
     * @return string
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Set groupnumber
     *
     * @param string $groupnumber
     */
    public function setGroupnumber($groupnumber)
    {
        $this->groupnumber = $groupnumber;
    }

    /**
     * Get groupnumber
     *
     * @return string
     */
    public function getGroupnumber()
    {
        return $this->groupnumber;
    }

    /**
     * Set classroom
     *
     * @param string $classroom
     */
    public function setClassroom($classroom)
    {
        $this->classroom = $classroom;
    }

    /**
     * Get classroom
     *
     * @return string
     */
    public function getClassroom()
    {
        return $this->classroom;
    }


    /**
     * Set section
     *
     * @param string $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * Get section
     *
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }


    /**
     * Set room
     *
     * @param integer $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * Get room
     *
     * @return integer
     */
    public function getRoom()
    {
        return $this->room;
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