<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 *
 * @ORM\Table(name="tek_periods")
 * @ORM\Entity()
 */
class Period
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
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $year;

    /**
     * @ORM\Column(name="orderInYear", type="integer")
     * @Assert\NotBlank()
     */
    private $orderInYear;

    /**
     * @ORM\Column(name="is_actual", type="boolean")
     */
    private $isActual;

    /**
     * @ORM\Column(name="is_editable", type="boolean")
     */
    private $isEditable;

    public function __construct()
    {
        $this->isActual = false;
        $this->isEditable = false;
        $this->orderInYear = 1;
    }

    public function __toString()
    {
        return $this->name . " - " . $this->year;
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

    /**
     * Set orderInYear
     *
     * @param integer $orderInYear
     */
    public function setOrderInYear($orderInYear)
    {
        $this->orderInYear = $orderInYear;
    }

    /**
     * Get orderInYear
     *
     * @return integer
     */
    public function getOrderInYear()
    {
        return $this->orderInYear;
    }

    /**
     * @inheritDoc
     */
    public function isActual()
    {
        return $this->isActual;
    }

    /**
     * @inheritDoc
     */
    public function setActual($value)
    {
        return $this->isActual = $value;
    }

    /**
     * @inheritDoc
     */
    public function isEditable()
    {
        return $this->isEditable;
    }

    /**
     * @inheritDoc
     */
    public function setEditable($value)
    {
        return $this->isEditable = $value;
    }
}