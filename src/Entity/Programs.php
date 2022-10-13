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
 * @ORM\Table(name="tek_programs")
 * @ORM\Entity()
 * @UniqueEntity("detail")
 */
class Programs
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="QuestionnaireGroup")
     * @JoinColumn(name="questionnairegroup_id", referencedColumnName="id", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $detail;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $date;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $teacher;

    /**
     * @ManyToOne(targetEntity="Course")
     * @JoinColumn(name="course_id", referencedColumnName="id", nullable=true)
     */
    private $course;

    /**
     * @ORM\Column(type="integer", nullable = true)
     * @Assert\Length(
     *      min = 1,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
     * )
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $versionp;


    /**
     * @ManyToOne(targetEntity="Period")
     * @JoinColumn(name="period_id", referencedColumnName="id", nullable=true)
     */
    private $period;

    public function __construct()
    {
        $this->detail = "";
    }

    public function __toString()
    {
        return $this->detail;
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
     * Set detail
     *
     * @param string $detail
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set teacher
     *
     * @param \App\Entity\User $teacher
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
     * Set date
     *
     * @param date $date
     */
    public function setDate()
    {
        $this->date =  new \DateTime("now");
    }

    /**
     * Get date
     *
     * @return date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set versionp
     *
     * @param integer $versionp
     */
    public function setVersionp($versionp)
    {
        $this->versionp = $versionp;
    }

    /**
     * Get versionp
     *
     * @return integer
     */
    public function getVersionp()
    {
        return $this->versionp;
    }
}