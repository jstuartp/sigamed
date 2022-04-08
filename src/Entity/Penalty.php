<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * @ORM\Table(name="tek_penalties")
 * @ORM\Entity()
 */
class Penalty
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 60,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $code;

    /**
     * @ManyToOne(targetEntity="Institution")
     * @JoinColumn(name="institution_id", referencedColumnName="id")
     */
    private $institution;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $maxPenalty;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $minPenalty;

    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set maxPenalty
     *
     * @param integer $maxPenalty
     */
    public function setMaxPenalty($maxPenalty)
    {
        $this->maxPenalty = $maxPenalty;
    }

    /**
     * Get maxPenalty
     *
     * @return integer
     */
    public function getMaxPenalty()
    {
        return $this->maxPenalty;
    }

    /**
     * Set minPenalty
     *
     * @param integer $minPenalty
     */
    public function setMinPenalty($minPenalty)
    {
        $this->minPenalty = $minPenalty;
    }

    /**
     * Get minPenalty
     *
     * @return integer
     */
    public function getMinPenalty()
    {
        return $this->minPenalty;
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