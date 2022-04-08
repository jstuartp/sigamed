<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_students_to_routes")
 * @ORM\Entity()
 */
class StudentToRoute
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Student")
     * @JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * @ManyToOne(targetEntity="Route")
     * @JoinColumn(name="route_id", referencedColumnName="id")
     */
    private $route;


    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->student . " in " . $this->route;
    }

    public function getCarneStudent()
    {
        return $this->student->getCarne();
    }

    public function getNameStudent()
    {
        return $this->student->getLastname() . " " . $this->student->getFirstname();
    }


    public function getGroupyearStudent()
    {
        return $this->student->getGroupyear();
    }


    public function getAdressStudent()
    {
        return $this->student->getAddress();
    }

    public function getDailyDescription()
    {
        return $this->student->getDailyDescription();
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
     * Set route
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Route $route
     */
    public function setRoute(\Tecnotek\ExpedienteBundle\Entity\Route $route)
    {
        $this->route = $route;
    }

    /**
     * Get route
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }



}