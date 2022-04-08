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
 * @ORM\Table(name="tek_course_entries")
 * @ORM\Entity()
 */
class CourseEntry
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
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="integer", name="max_value")
     * @Assert\NotBlank()
     */
    private $maxValue;

    /**
     * @ORM\Column(type="decimal", precision=4)
     * @Assert\NotBlank()
     */
    private $percentage;

    /**
     * @ORM\Column(type="integer", name="sort_order")
     * @Assert\NotBlank()
     */
    private $sortOrder;

    /**
     * @ManyToOne(targetEntity="CourseClass")
     * @JoinColumn(name="course_class_id", referencedColumnName="id")
     */
    private $courseClass;

    /**
     * @ManyToOne(targetEntity="CourseEntry")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var Childrens
     *
     * @ORM\OneToMany(targetEntity="CourseEntry", mappedBy="parent")
     */
    private $childrens;

    /**
     * @var subentries
     *
     * @ORM\OneToMany(targetEntity="SubCourseEntry", mappedBy="parent")
     */
    private $subentries;


    public function __construct()
    {
        $this->maxValue = 100;
        $this->percentage = 0;
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
     * Set maxValue
     *
     * @param integer $maxValue
     */
    public function setMaxValue($maxValue)
    {
        $this->maxValue = $maxValue;
    }

    /**
     * Get maxValue
     *
     * @return integer 
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }

    /**
     * Set percentage
     *
     * @param double $percentage
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * Get percentage
     *
     * @return double
     */
    public function getPercentage()
    {
        return $this->percentage;
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
     * Set courseClass
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\CourseClass $courseClass
     */
    public function setCourseClass(\Tecnotek\ExpedienteBundle\Entity\CourseClass $courseClass)
    {
        $this->courseClass = $courseClass;
    }

    /**
     * Get courseClass
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\CourseClass
     */
    public function getCourseClass()
    {
        return $this->courseClass;
    }

    /**
     * Set parent
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\CourseEntry $parent
     */
    public function setParent(\Tecnotek\ExpedienteBundle\Entity\CourseEntry $parent)
    {
        $this->parent = $parent;
    }

    public function removeParent()
    {
        $this->parent = null;
    }

    /**
     * Get parent
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\CourseEntry
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function getChildrens(){
        return $this->childrens;
    }

    public function getSubentries(){
        return $this->subentries;
    }
}