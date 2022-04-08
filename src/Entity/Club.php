<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 *
 * @ORM\Table(name="tek_clubs")
 * @ORM\Entity()
 * @UniqueEntity("name")
 */
class Club
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
     * @ORM\Column(type="string", length=120, nullable = true)
     * @Assert\Length(
     *      max = 120,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $coordinator;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $day;

    /**
     * @ORM\Column(type="string", length=12, nullable = true)
     */
    private $timeI;

    /**
     * @ORM\Column(type="string", length=12, nullable = true)
     */
    private $timeO;

    /**
     * @var ArrayCollection $students
     * @ORM\ManyToMany(targetEntity="Student", inversedBy="clubs", cascade={"all"})
     * @ORM\JoinTable(
     *      name="club_student",
     *      joinColumns={@ORM\JoinColumn(name="club_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="student_id", referencedColumnName="id")}
     * )
     * @ORM\OrderBy({"lastname" = "ASC"})
     */
    private $students;

    public function __construct()
    {
        $this->students = new ArrayCollection();
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
     * Set coordinator
     *
     * @param string $coordinator
     */
    public function setCoordinator($coordinator)
    {
        $this->coordinator = $coordinator;
    }

    /**
     * Get coordinator
     *
     * @return string 
     */
    public function getCoordinator()
    {
        return $this->coordinator;
    }

    /**
     * Set day
     *
     * @param integer $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * Get day
     *
     * @return integer 
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set timeI
     *
     * @param String $timeI
     */
    public function setTimeI($timeI)
    {
        $this->timeI = $timeI;
    }

    /**
     * Get timeI
     *
     * @return String
     */
    public function getTimeI()
    {
        return $this->timeI;
    }

    /**
     * Set timeO
     *
     * @param String $timeO
     */
    public function setTimeO($timeO)
    {
        $this->timeO = $timeO;
    }

    /**
     * Get timeO
     *
     * @return String
     */
    public function getTimeO()
    {
        return $this->timeO;
    }

    /**
     * @inheritDoc
     */
    public function getStudents()
    {
        return (isset($this->students))? $this->students:new ArrayCollection();
    }
}