<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_student_extra_tests")
 * @ORM\Entity()
 */
class StudentExtraTest
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=4)
     * @Assert\NotBlank()
     */
    private $qualification;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $number;

    /**
     * @ManyToOne(targetEntity="Course")
     * @JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @ManyToOne(targetEntity="StudentYear")
     * @JoinColumn(name="student_year_id", referencedColumnName="id")
     */
    private $studentYear;


    public function __construct()
    {
        $this->qualification = -1;
        $this->number = 1;
    }

    public function __toString()
    {
        return $this->studentYear;
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
     * Set qualification
     *
     * @param double $qualification
     */
    public function setQualification($qualification)
    {
        $this->qualification = $qualification;
    }

    /**
     * Get qualification
     *
     * @return double
     */
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * Set number
     *
     * @param integer $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set studentYear
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\StudentYear $studentYear
     */
    public function setStudentYear(\Tecnotek\ExpedienteBundle\Entity\StudentYear $studentYear)
    {
        $this->studentYear = $studentYear;
    }

    /**
     * Get studentYear
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\StudentYear
     */
    public function getStudentYear()
    {
        return $this->studentYear;
    }

    /**
     * Set course
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Course $course
     */
    public function setCourse(\Tecnotek\ExpedienteBundle\Entity\Course $course)
    {
        $this->course = $course;
    }

    /**
     * Get $course
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

}