<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_special_qualifications")
 * @ORM\Entity()
 */
class SpecialQualification
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
    private $mainText;

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
    private $secondText;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $sortOrder;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ManyToOne(targetEntity="SpecialQualificationsForm")
     * @JoinColumn(name="form_id", referencedColumnName="id")
     */
    private $form;

    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->mainText;
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
     * Set mainText
     *
     * @param string $mainText
     */
    public function setMainText($mainText)
    {
        $this->mainText = $mainText;
    }

    /**
     * Get mainText
     *
     * @return string 
     */
    public function getMainText()
    {
        return $this->mainText;
    }

    /**
     * Set secondText
     *
     * @param string $secondText
     */
    public function setSecondText($secondText)
    {
        $this->secondText = $secondText;
    }

    /**
     * Get secondText
     *
     * @return string
     */
    public function getSecondText()
    {
        return $this->secondText;
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

    /**
     * Set form
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\SpecialQualificationsForm $form
     */
    public function setForm(\Tecnotek\ExpedienteBundle\Entity\SpecialQualificationsForm $form)
    {
        $this->form = $form;
    }

    /**
     * Get form
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\SpecialQualificationsForm
     */
    public function getForm()
    {
        return $this->form;
    }
}