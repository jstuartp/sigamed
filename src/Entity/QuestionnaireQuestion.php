<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_questionnaire_questions")
 * @ORM\Entity()
 */
class QuestionnaireQuestion
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
    private $maintext;

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
    private $secondtext;

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
     * @ManyToOne(targetEntity="Questionnaire")
     * @JoinColumn(name="questionnaire_id", referencedColumnName="id")
     */
    private $questionnaire;

    /**
     * @ManyToOne(targetEntity="QuestionnaireQuestion")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var children
     *
     * @ORM\OneToMany(targetEntity="QuestionnaireQuestion", mappedBy="parent", cascade={"all"})
     */
    private $children;



    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->maintext;
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
     * Set maintext
     *
     * @param string $maintext
     */
    public function setMainText($maintext)
    {
        $this->maintext = $maintext;
    }

    /**
     * Get maintext
     *
     * @return string 
     */
    public function getMainText()
    {
        return $this->maintext;
    }

    /**
     * Set secondtext
     *
     * @param string $secondtext
     */
    public function setSecondText($secondtext)
    {
        $this->secondtext = $secondtext;
    }

    /**
     * Get secondtext
     *
     * @return string
     */
    public function getSecondText()
    {
        return $this->secondtext;
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
     * Get sortorder
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

    /**
     * Set questionnaire
     *
     * @param \App\Entity\Questionnaire $questionnaire
     */
    public function setBus(\App\Entity\Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    /**
     * Get questionnaire
     *
     * @return \App\Entity\Questionnaire
     */
    public function getQuestionnaire()
    {
        return $this->questionnaire;
    }

    /**
     * Set parent
     *
     * @param \App\Entity\QuestionnaireQuestion $parent
     */
    public function setParent(\App\Entity\QuestionnaireQuestion $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return \App\Entity\QuestionnaireQuestion
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren(){
        return $this->children;
    }
}