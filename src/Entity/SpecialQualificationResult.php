<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_special_qualification_results")
 * @ORM\Entity()
 */
class SpecialQualificationResult
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
     *      min = 1,
     *      max = 255,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $mainText;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $periodNumber;

    /**
     * @ManyToOne(targetEntity="SpecialQualification")
     * @JoinColumn(name="special_qualification_id", referencedColumnName="id")
     */
    private $specialQualification;

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

    public function setSpecialQualification($specialQualification)
    {
        $this->specialQualification = $specialQualification;
    }

    public function getSpecialQualification()
    {
        return $this->specialQualification;
    }

    public function setStudentYear($studentYear)
    {
        $this->studentYear = $studentYear;
    }

    public function getStudentYear()
    {
        return $this->studentYear;
    }

    public function setPeriodNumber($periodNumber)
    {
        $this->periodNumber = $periodNumber;
    }

    public function getPeriodNumber()
    {
        return $this->periodNumber;
    }


}