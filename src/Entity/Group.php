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
 * @ORM\Table(name="tek_groups")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Group
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
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ManyToOne(targetEntity="Period")
     * @JoinColumn(name="period_id", referencedColumnName="id")
     */
    private $period;

    /**
     * @ManyToOne(targetEntity="Grade")
     * @JoinColumn(name="grade_id", referencedColumnName="id")
     */
    private $grade;

    /**
     * @ManyToOne(targetEntity="Institution")
     * @JoinColumn(name="institution_id", referencedColumnName="id")
     */
    private $institution;

    /**
     * @var AssignedTeachers
     *
     * @ORM\OneToMany(targetEntity="AssignedTeacher", mappedBy="group", cascade={"persist", "remove"})
     */
    private $assignedTeachers;

    private $students;

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
     * Set grade
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Grade $grade
     */
    public function setGrade(\Tecnotek\ExpedienteBundle\Entity\Grade $grade)
    {
        $this->grade = $grade;
    }

    /**
     * Get grade
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Grade
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set teacher
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\User $teacher
     */
    public function setTeacher(\Tecnotek\ExpedienteBundle\Entity\User $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Get teacher
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\User
     */
    public function getTeacher()
    {
        return $this->teacher;
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

    public function setStudents($list){
        $this->students = $list;
    }

    public function getStudents(){
        return $this->students;
    }
}