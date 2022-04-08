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
 * @ORM\Table(name="tek_assigned_courses")
 * @ORM\Entity()
 */
class AssignedCourse
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @ORM\Column(name="weight", type="string", length=30, nullable=true)
     */
    private $weight;

    /**
     * @ManyToOne(targetEntity="Course")
     * @JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

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
     * Set course
     *
     * @param \App\Entity\Course $course
     */
    public function setCourse(\App\Entity\Course $course)
    {
        $this->course = $course;
    }

    /**
     * Get course
     *
     * @return \App\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
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