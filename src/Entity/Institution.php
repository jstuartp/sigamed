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
 * @ORM\Table(name="tek_institutions")
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="App\Repository\InstitutionRepository")
 */
class Institution
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

    private $students;

    /**
     * @var absenceTypePoints
     *
     * @ORM\OneToMany(targetEntity="AbsenceTypePoints", mappedBy="institution", cascade={"all"})
     */
    private $absenceTypePoints;

    /**
     * @var ArrayCollection $questionnaires
     * @ORM\ManyToMany(targetEntity="Questionnaire", mappedBy="institutions")
     */
    private $questionnaires;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="institutions")
     */
    private $usersAccess;

    public function __construct()
    {
        $this->questionnaires = new ArrayCollection();
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

    public function setStudents($list){
        $this->students = $list;
    }

    public function getStudents(){
        return $this->students;
    }

    public function getAbsenceTypePoints(){
        return $this->absenceTypePoints;
    }

    /**
     * @inheritDoc
     */
    public function getQuestionnaires()
    {
        return $this->questionnaires->toArray();
    }
}