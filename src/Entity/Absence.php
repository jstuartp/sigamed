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
 * @ORM\Table(name="tek_absences")
 * @ORM\Entity()
 */
class Absence
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "Comentarios mayores a {{ limit }} caracteres",
     *      maxMessage = "Comentarios menores a {{ limit }} caracteres"
     * )
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    private $justify;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $date;

    /**
     * @ManyToOne(targetEntity="StudentYear")
     * @JoinColumn(name="studentYear_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $studentYear;

    /**
     * @ManyToOne(targetEntity="AbsenceType")
     * @JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    public function __construct()
    {
        $this->justify = false;
        $this->comments = "";
        $this->date = new \DateTime();
    }

    public function __toString()
    {
        return "Ausencia #" . $this->id . ": " . $this->student;
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
     * Set justify
     *
     * @param boolean $justify
     */
    public function setJustify($justify)
    {
        $this->justify = $justify;
    }

    /**
     * Get justify
     *
     * @return boolean
     */
    public function getJustify()
    {
        return $this->justify;
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
     * Get studentYear
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\StudentYear
     */
    public function getStudentYearId()
    {
        return $this->studentYear->getID();
    }

    /**
     * Set type
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\AbsenceType $type
     */
    public function setType(\Tecnotek\ExpedienteBundle\Entity\AbsenceType $type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\AbsenceType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get type
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\AbsenceType
     */
    public function getTypeId()
    {
        return $this->type->getID();
    }

}