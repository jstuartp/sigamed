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
 * @ORM\Table(name="tek_sub_course_entries")
 * @ORM\Entity()
 */
class SubCourseEntry
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
     * @ORM\Column(type="decimal", precision=3)
     * @Assert\NotBlank()
     */
    private $percentage;

    /**
     * @ORM\Column(type="integer", name="sort_order")
     * @Assert\NotBlank()
     */
    private $sortOrder;

    /**
     * @ManyToOne(targetEntity="CourseEntry")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ManyToOne(targetEntity="Group")
     * @JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @var studentsQualifications
     *
     * @ORM\OneToMany(targetEntity="StudentQualification", mappedBy="subCourseEntry", cascade={"persist", "remove"})
     */
    private $studentsQualifications;

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
     * Set group
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Group $group
     */
    public function setGroup(\Tecnotek\ExpedienteBundle\Entity\Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
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

    /**
     * Get parent
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\CourseEntry
     */
    public function getParent()
    {
        return $this->parent;
    }

}