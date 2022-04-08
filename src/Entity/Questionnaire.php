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
 * @ORM\Table(name="tek_questionnaires")
 * @ORM\Entity()
 * @UniqueEntity("name")
 * @ORM\Entity(repositoryClass="App\Repository\QuestionnaireRepository")
 */
class Questionnaire
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
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $sortorder;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(name="enable_for_teacher", type="boolean")
     */
    private $enableForTeacher;

    /**
     * @var questions
     *
     * @ORM\OneToMany(targetEntity="QuestionnaireQuestion", mappedBy="questionnaire", cascade={"all"})
     * @ORM\OrderBy({"sortorder" = "ASC"})
     */
    private $questions;

    /**
     * @ManyToOne(targetEntity="QuestionnaireGroup")
     * @JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @var ArrayCollection $institutions
     * @ORM\ManyToMany(targetEntity="Institution", inversedBy="questionnaires", cascade={"all"})
     * @ORM\JoinTable(
     *      name="institution_questionnaires",
     *      joinColumns={@ORM\JoinColumn(name="questionnaire_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="institution_id", referencedColumnName="id")}
     * )
     */
    private $institutions;

    public function __construct()
    {
        $this->institutions = new ArrayCollection();
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
     * Set sortorder
     *
     * @param integer $sortorder
     */
    public function setSortOrder($sortorder)
    {
        $this->sortorder = $sortorder;
    }

    /**
     * Get sortOrder
     *
     * @return integer 
     */
    public function getSortOrder()
    {
        return $this->sortorder;
    }

    /**
     * Set type
     *
     * @param integer $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    public function setEnabledForTeacher($enableForTeacher){
        $this->enableForTeacher = $enableForTeacher;
    }

    public function isEnabledForTeacher()
    {
        return $this->enableForTeacher;
    }

    public function getQuestions(){
        return $this->questions;
    }

    /**
     * @inheritDoc
     */
    public function getInstitutions()
    {
        return (isset($this->institutions))? $this->institutions:new ArrayCollection();
    }

    public function getGroup(){
        return $this->group;
    }

    public function setGroup(QuestionnaireGroup $group){
        $this->group = $group;
    }

    /**
     * Set info
     *
     * @param string $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }
}