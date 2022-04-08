<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_student_extra_points")
 * @ORM\Entity()
 */
class StudentExtraPoints
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
    private $points;

    /**
     * @ORM\Column(type="integer")
     */
    private $typePoints;

    /**
     * @ManyToOne(targetEntity="Course")
     * @JoinColumn(name="course_id", referencedColumnName="id", nullable=true)
     */
    private $course;

    /**
     * @ManyToOne(targetEntity="StudentYear")
     * @JoinColumn(name="student_year_id", referencedColumnName="id")
     */
    private $studentYear;


    public function __construct()
    {
        $this->points = 0;
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
     * Set points
     *
     * @param double $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * Get points
     *
     * @return double
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set typePoints
     *
     * @param int $typePoints
     */
    public function setTypePoints($typePoints)
    {
        $this->typePoints = $typePoints;
    }

    /**
     * Get typePoints
     *
     * @return int
     */
    public function getTypePoints()
    {
        return $this->typePoints;
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
     * Set Course
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Course $Course
     */
    public function setCourse($Course)
    {
        $this->course = $Course;
    }

    public function removeCourse()
    {
        $this->course = null;
    }

    /**
     * Get Course
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

}