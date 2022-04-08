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
 * @ORM\Table(name="tek_assigned_teachers")
 * @ORM\Entity()
 */
class AssignedTeacher
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
     * @ManyToOne(targetEntity="Group")
     * @JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @ManyToOne(targetEntity="CourseClass")
     * @JoinColumn(name="course_class_id", referencedColumnName="id")
     */
    private $courseClass;

    public function __construct()
    {

    }

    public function __toString()
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
     * Set teacher
     *
     * @param \App\Entity\User $user
     */
    public function setTeacher(\App\Entity\User $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Get teacher
     *
     * @return \App\Entity\User
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set courseClass
     *
     * @param \App\Entity\CourseClass $courseClass
     */
    public function setCourseClass(\App\Entity\CourseClass $courseClass)
    {
        $this->courseClass = $courseClass;
    }

    /**
     * Get courseClass
     *
     * @return \App\Entity\CourseClass
     */
    public function getCourseClass()
    {
        return $this->courseClass;
    }

    /**
     * Set group
     *
     * @param \App\Entity\Group $group
     */
    public function setGroup(\App\Entity\Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return \App\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}