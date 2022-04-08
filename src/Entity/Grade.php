<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 *
 * @ORM\Table(name="tek_grades")
 * @ORM\Entity()
 * @UniqueEntity("number")
 * @ORM\Entity(repositoryClass="App\Repository\GradeRepository")
 */
class Grade
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
    private $number;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $notamin;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    private $isspecial;

    private $students;

    public function __construct()
    {
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
     * Set number
     *
     * @param integer $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    public function setStudents($list){
        $this->students = $list;
    }

    public function getStudents(){
        return $this->students;
    }

    /**
     * Set notamin
     *
     * @param integer $notamin
     */
    public function setNotamin($notamin)
    {
        $this->notamin = $notamin;
    }

    /**
     * Get notamin
     *
     * @return integer
     */
    public function getNotamin()
    {
        return $this->notamin;
    }

    public function setIsspecial($isspecial)
    {
        $this->isspecial = $isspecial;
    }

    public function getIsspecial()
    {
        return $this->isspecial;
    }
}