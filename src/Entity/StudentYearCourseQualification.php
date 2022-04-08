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
 * @ORM\Table(name="tek_student_year_course_qualifications",uniqueConstraints={@ORM\UniqueConstraint(name="unique_qualification_idx", columns={"course_class_id", "student_year_id"})})
 * @ORM\Entity()
 */
class StudentYearCourseQualification
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
     * @ManyToOne(targetEntity="CourseClass")
     * @JoinColumn(name="course_class_id", referencedColumnName="id")
     */
    private $courseClass;

    /**
     * @ManyToOne(targetEntity="StudentYear")
     * @JoinColumn(name="student_year_id", referencedColumnName="id")
     */
    private $studentYear;


    public function __construct()
    {
        $this->qualification = -1;
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
     * Set courseClass
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\CourseClass $courseClass
     */
    public function setCourseClass(\Tecnotek\ExpedienteBundle\Entity\CourseClass $courseClass)
    {
        $this->courseClass = $courseClass;
    }

    /**
     * Get courseClass
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\CourseClass
     */
    public function getCourseClass()
    {
        return $this->courseClass;
    }

}