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
 * @ORM\Table(name="tek_special_qualifications_forms")
 * @ORM\Entity()
 */
class SpecialQualificationsForm
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 150,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer", name="sort_order")
     * @Assert\NotBlank()
     */
    private $sortOrder;

    /**
     * @ManyToOne(targetEntity="Grade")
     * @JoinColumn(name="grade_id", referencedColumnName="id")
     */
    private $grade;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
     * )
     */
    private $entryType;

    /**
     * @ORM\Column(name="shows_on_period_one", type="boolean")
     */
    private $showsOnPeriodOne;

    /**
     * @ORM\Column(name="shows_on_period_two", type="boolean")
     */
    private $showsOnPeriodTwo;

    /**
     * @ORM\Column(name="shows_on_period_three", type="boolean")
     */
    private $showsOnPeriodThree;

    /**
     * @ORM\Column(name="must_include_comments", type="boolean")
     */
    private $mustIncludeComments;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $columnNumber;

    /**
     * @var questions
     *
     * @ORM\OneToMany(targetEntity="SpecialQualification", mappedBy="form", cascade={"all"})
     * @ORM\OrderBy({"sortOrder" = "ASC"})
     */
    private $questions;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $year;


    public function __construct()
    {
        $this->showsOnPeriodOne = false;
        $this->showsOnPeriodTwo = false;
        $this->showsOnPeriodThree = false;
        $this->mustIncludeComments = false;

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
     * Set sortOrder
     *
     * @param integer $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
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
     * Set entryType
     *
     * @param integer $entryType
     */
    public function setEntryType($entryType)
    {
        $this->entryType = $entryType;
    }

    /**
     * Get entryType
     *
     * @return integer
     */
    public function getEntryType()
    {
        return $this->entryType;
    }

    public function setShowsOnPeriodOne($showsOnPeriodOne)
    {
        $this->showsOnPeriodOne = $showsOnPeriodOne;
    }

    public function getShowsOnPeriodOne()
    {
        return $this->showsOnPeriodOne;
    }

    public function setShowsOnPeriodThree($showsOnPeriodThree)
    {
        $this->showsOnPeriodThree = $showsOnPeriodThree;
    }

    public function getShowsOnPeriodThree()
    {
        return $this->showsOnPeriodThree;
    }

    public function setShowsOnPeriodTwo($showsOnPeriodTwo)
    {
        $this->showsOnPeriodTwo = $showsOnPeriodTwo;
    }

    public function getShowsOnPeriodTwo()
    {
        return $this->showsOnPeriodTwo;
    }

    public function getQuestions(){
        return $this->questions;
    }

    public function setMustIncludeComments($mustIncludeComments)
    {
        $this->mustIncludeComments = $mustIncludeComments;
    }

    public function getMustIncludeComments()
    {
        return $this->mustIncludeComments;
    }

    public function setColumnNumber($columnNumber)
    {
        $this->columnNumber = $columnNumber;
    }

    public function getColumnNumber()
    {
        return $this->columnNumber;
    }

    /**
     * Set year
     *
     * @param integer $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }
}