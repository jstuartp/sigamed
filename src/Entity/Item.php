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
 * @ORM\Table(name="tek_item")
 * @ORM\Entity()
 * @UniqueEntity("code")
 */
class Item
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=60)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=160)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 60,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200, nullable = true)
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $description;

     /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
      * @Assert\Length(
      *      min = 1,
      *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
      * )
     * 1: Bodega, 2: Prestamo
     */
    private $status;

    /**
     * @ManyToOne(targetEntity="CategoryItem")
     * @JoinColumn(name="category_item_id", referencedColumnName="id")
     */
    private $category;


    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->code . " :: " . $this->name;
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
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set code
     *
     * @param integer $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
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
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param \App\Entity\CategoryItem $category
     */
    public function setCategory(\App\Entity\CategoryItem $category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return \App\Entity\CategoryItem
     */
    public function getCategory()
    {
        return $this->category;
    }


}