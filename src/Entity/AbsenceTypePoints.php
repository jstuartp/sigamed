<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_absence_types_points")
 * @ORM\Entity()
 */
class AbsenceTypePoints
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="AbsenceType")
     * @JoinColumn(name="absence_type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $absenceType;

    /**
     * @ManyToOne(targetEntity="Institution")
     * @JoinColumn(name="institution_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $institution;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $points;

    public function __construct()
    {
    }

    public function __toString()
    {
        return "AbsenceTypePoints: " . $this->id;
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
     * Set absenceType
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\AbsenceType $absenceType
     */
    public function setAbsenceType(\Tecnotek\ExpedienteBundle\Entity\AbsenceType $absenceType)
    {
        $this->absenceType = $absenceType;
    }

    /**
     * Get absenceType
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\AbsenceType
     */
    public function getAbsenceType()
    {
        return $this->absenceType;
    }

    /**
     * Set institution
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Institution $institution
     */
    public function setInstitution(\Tecnotek\ExpedienteBundle\Entity\Institution $institution)
    {
        $this->institution = $institution;
    }

    /**
     * Get institution
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

}