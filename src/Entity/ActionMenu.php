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
 * @ORM\Table(name="tek_actions_menu")
 * @ORM\Entity()
 */
class ActionMenu
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
    private $label;

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
    private $route;

    /**
     * @ORM\Column(type="integer", name="reference")
     * @Assert\NotBlank()
     */
    private $reference;

    /**
     * @ORM\Column(type="integer", name="sort_order")
     * @Assert\NotBlank()
     */
    private $sortOrder;

    /**
     * @ManyToOne(targetEntity="ActionMenu")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var childrens
     * @ORM\OneToMany(targetEntity="ActionMenu", mappedBy="parent", cascade={"persist", "remove"})
     * @ORM\OrderBy({"sortOrder" = "ASC"})
     */
    private $childrens;

    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->label;
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
     * Set label
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set route
     *
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set reference
     *
     * @param integer $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * Get reference
     *
     * @return integer 
     */
    public function getReference()
    {
        return $this->reference;
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
     * Set parent
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\ActionMenu $parent
     */
    public function setParent(\Tecnotek\ExpedienteBundle\Entity\ActionMenu $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\ActionMenu
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function getChildrens(){
        return $this->childrens;
    }
}