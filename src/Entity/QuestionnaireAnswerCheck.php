<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_questionnaire_answers_checks")
 * @ORM\Entity()
 */
class QuestionnaireAnswerCheck
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3000)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 3000,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $maintext;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 1000,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $secondtext;

    /**
     * @ManyToOne(targetEntity="QuestionnaireQuestion")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @ManyToOne(targetEntity="Programs")
     * @JoinColumn(name="program_id", referencedColumnName="id")
     */
    private $program;


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
     * Set question
     *
     * @param \App\Entity\QuestionnaireQuestion $question
     */
    public function setQuestion(\App\Entity\QuestionnaireQuestion $question)
    {
        $this->question = $question;
    }

    /**
     * Get question
     *
     * @return \App\Entity\QuestionnaireQuestion
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set program
     *
     * @param \App\Entity\Programs $program
     */
    public function setProgram(\App\Entity\Programs $program)
    {
        $this->program = $program;
    }

    /**
     * Get program
     *
     * @return \App\Entity\Programs
     */
    public function getProgram()
    {
        return $this->program;
    }
}