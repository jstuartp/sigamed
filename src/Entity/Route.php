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
 * @ORM\Table(name="tek_route")
 * @ORM\Entity()
 * @UniqueEntity("code")
 */
class Route
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
     * )
     */
    private $code;

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
     * @ORM\Column(type="string", length=120, nullable = true)
     * @Assert\Length(
     *      max = 120,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $mapUrl;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
     * )
     * 1: Normal, 2: Club
     */
    private $routeType;

    /**
     * @ManyToOne(targetEntity="Zone")
     * @JoinColumn(name="zone_id", referencedColumnName="id")
     */
    private $zone;

    /**
     * @ManyToOne(targetEntity="Buseta")
     * @JoinColumn(name="bus_id", referencedColumnName="id")
     */
    private $bus;

    /**
     * @ManyToOne(targetEntity="Institution")
     * @JoinColumn(name="institution_id", referencedColumnName="id")
     */
    private $institution;

    /**
     * @var Students
     *
     * @ORM\OneToMany(targetEntity="Student", mappedBy="route")
     * @ORM\OrderBy({"lastname" = "ASC"})
     */
    private $students;

    /**
     * @var StudentsToRoute
     *
     * @ORM\OneToMany(targetEntity="StudentToRoute", mappedBy="route")
     */
    private $studentsToRoute;

    public function __construct()
    {
        $this->mapUrl = "";
        $this->routeType = 1;
    }

    public function __toString()
    {
        return $this->code . " :: " . $this->name;
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
     * Set routeType
     *
     * @param integer $routeType
     */
    public function setRouteType($routeType)
    {
        $this->routeType = $routeType;
    }

    /**
     * Get routeType
     *
     * @return integer
     */
    public function getRouteType()
    {
        return $this->routeType;
    }

    /**
     * Set code
     *
     * @param integer $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
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
     * Set mapUrl
     *
     * @param string $mapUrl
     */
    public function setMapUrl($mapUrl)
    {
        $this->mapUrl = $mapUrl;
    }

    /**
     * Get mapUrl
     *
     * @return string 
     */
    public function getMapUrl()
    {
        return $this->mapUrl;
    }

    /**
     * Set zone
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Zone $zone
     */
    public function setZone(\Tecnotek\ExpedienteBundle\Entity\Zone $zone)
    {
        $this->zone = $zone;
    }

    /**
     * Get zone
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set bus
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Buseta $bus
     */
    public function setBus(\Tecnotek\ExpedienteBundle\Entity\Buseta $bus)
    {
        $this->bus = $bus;
    }

    /**
     * Get bus
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Buseta
     */
    public function getBus()
    {
        return $this->bus;
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

    public function getStudents(){
        return $this->students;
    }

    public function getStudentsToRoute(){
        return $this->studentsToRoute;
    }
}