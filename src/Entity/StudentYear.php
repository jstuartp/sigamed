<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_students_year")
 * @ORM\Entity()
 */
class StudentYear
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $conducta = "100";

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $periodAverageScore = "100";

    /**
     * @ORM\Column(type="integer")
     */
    private $periodHonor= "0";

    /**
     * @ManyToOne(targetEntity="Student")
     * @JoinColumn(name="student_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $student;

    /**
     * @ManyToOne(targetEntity="Period")
     * @JoinColumn(name="period_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $period;

    /**
     * @ManyToOne(targetEntity="Group")
     * @JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->student . " in " . $this->period;
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
     * Set conducta
     *
     * @param double $conducta
     */
    public function setConducta($conducta)
    {
        $this->conducta = $conducta;
    }

    /**
     * Get conducta
     *
     * @return double
     */
    public function getConducta()
    {
        return $this->conducta;
    }

    /**
     * Set periodAverageScore
     *
     * @param double $periodAverageScore
     */
    public function setPeriodAverageScore($periodAverageScore)
    {
        $this->periodAverageScore = $periodAverageScore;
    }

    /**
     * Get periodAverageScore
     *
     * @return double
     */
    public function getPeriodAverageScore()
    {
        return $this->periodAverageScore;
    }

    /**
     * Set periodHonor
     *
     * @param int $periodHonor
     */
    public function setPeriodHonor($periodHonor)
    {
        $this->periodHonor = $periodHonor;
    }

    /**
     * Get periodHonor
     *
     * @return int     */
    public function getPeriodHonor()
    {
        return $this->periodHonor;
    }

    /**
     * Set student
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Student $student
     */
    public function setStudent(\Tecnotek\ExpedienteBundle\Entity\Student $student)
    {
        $this->student = $student;
    }

    /**
     * Get student
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set period
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Period $period
     */
    public function setPeriod(\Tecnotek\ExpedienteBundle\Entity\Period $period)
    {
        $this->period = $period;
    }

    /**
     * Get period
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Period
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set group
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Group $group
     */
    public function setGroup(\Tecnotek\ExpedienteBundle\Entity\Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    public function removeFromGroup(){
        $this->group = null;
    }
}