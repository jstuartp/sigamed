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
 * @ORM\Table(name="tek_bus")
 * @ORM\Entity()
 * @UniqueEntity("licensePlate")
 */
class Buseta
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
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $licensePlate;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $color;

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
    private $driver;

    /**
     * @ORM\Column(type="string", length=60, nullable = true)
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
     * )
     */
    private $capacity;

    /**
     * @ORM\Column(type="date", nullable = true)
     */
    private $riteve;

    /**
     * @ORM\Column(type="date", nullable = true)
     */
    private $ins;

    /**
     * @ORM\Column(type="date", nullable = true)
     */
    private $permission;

    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->name . " :: " . $this->licensePlate;
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
     * Set licensePlate
     *
     * @param string $licensePlate
     */
    public function setLicensePlate($licensePlate)
    {
        $this->licensePlate = $licensePlate;
    }

    /**
     * Get licensePlate
     *
     * @return string 
     */
    public function getLicensePlate()
    {
        return $this->licensePlate;
    }

    /**
     * Set color
     *
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set driver
     *
     * @param string $driver
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get driver
     *
     * @return string 
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * Get capacity
     *
     * @return integer 
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set riteve
     *
     * @param date $riteve
     */
    public function setRiteve($riteve)
    {
        $this->riteve = $riteve;
    }

    /**
     * Get riteve
     *
     * @return date 
     */
    public function getRiteve()
    {
        return $this->riteve;
    }

    /**
     * Set ins
     *
     * @param date $ins
     */
    public function setIns($ins)
    {
        $this->ins = $ins;
    }

    /**
     * Get ins
     *
     * @return date 
     */
    public function getIns()
    {
        return $this->ins;
    }

    /**
     * Set permission
     *
     * @param date $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * Get permission
     *
     * @return date 
     */
    public function getPermission()
    {
        return $this->permission;
    }

}