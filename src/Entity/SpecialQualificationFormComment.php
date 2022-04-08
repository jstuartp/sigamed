<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_special_qualification_form_comments")
 * @ORM\Entity()
 */
class SpecialQualificationFormComment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 500,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $mainText;

    /**
     * @ManyToOne(targetEntity="SpecialQualificationsForm")
     * @JoinColumn(name="special_qualifications_form_id", referencedColumnName="id")
     */
    private $specialQualificationsForm;

    /**
     * @ManyToOne(targetEntity="StudentYear")
     * @JoinColumn(name="student_year_id", referencedColumnName="id")
     */
    private $studentYear;

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

    public function setSpecialQualificationsForm($specialQualificationsForm)
    {
        $this->specialQualificationsForm = $specialQualificationsForm;
    }

    public function getSpecialQualificationsForm()
    {
        return $this->specialQualificationsForm;
    }

    public function setStudentYear($studentYear)
    {
        $this->studentYear = $studentYear;
    }

    public function getStudentYear()
    {
        return $this->studentYear;
    }
}