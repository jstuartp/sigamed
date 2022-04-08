<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_student_penalties")
 * @ORM\Entity()
 */
class StudentPenalty
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $date;

    /**
     * @ManyToOne(targetEntity="Penalty")
     * @JoinColumn(name="penalty_id", referencedColumnName="id")
     */
    private $penalty;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $pointsPenalty;

    /**
     * @ManyToOne(targetEntity="StudentYear")
     * @JoinColumn(name="student_year_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $studentYear;


    public function __construct()
    {
        $this->comments = "";
        $this->date = new \DateTime();
    }

    public function __toString()
    {
        return $this->studentYear . " - " . $this->penalty . ": " . $this->id;
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
     * Set penalty
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Penalty $penalty
     */
    public function setPenalty(\Tecnotek\ExpedienteBundle\Entity\Penalty $penalty)
    {
        $this->penalty = $penalty;
    }

    /**
     * Get penalty
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Penalty
     */
    public function getPenalty()
    {
        return $this->penalty;
    }

    /**
     * Set comments
     *
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set date
     *
     * @param date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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
     * Set pointsPenalty
     *
     * @param integer $pointsPenalty
     */
    public function setPointsPenalty($pointsPenalty)
    {
        $this->pointsPenalty = $pointsPenalty;
    }

    /**
     * Get pointsPenalty
     *
     * @return integer
     */
    public function getPointsPenalty()
    {
        return $this->pointsPenalty;
    }
}