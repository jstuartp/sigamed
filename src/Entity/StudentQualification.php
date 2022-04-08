<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_student_qualifications")
 * @ORM\Entity(repositoryClass="Tecnotek\ExpedienteBundle\Repository\CustomRepository")
 */
class StudentQualification
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
     * @ManyToOne(targetEntity="SubCourseEntry")
     * @JoinColumn(name="sub_course_id", referencedColumnName="id")
     */
    private $subCourseEntry;

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
     * Set subCourseEntry
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\SubCourseEntry $subCourseEntry
     */
    public function setSubCourseEntry(\Tecnotek\ExpedienteBundle\Entity\SubCourseEntry $subCourseEntry)
    {
        $this->subCourseEntry = $subCourseEntry;
    }

    /**
     * Get subCourseEntry
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\SubCourseEntry
     */
    public function getSubCourseEntry()
    {
        return $this->subCourseEntry;
    }

}