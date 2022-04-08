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
 * @ORM\Table(name="tek_diac")
 * @ORM\Entity()
 * @UniqueEntity("licensePlate")
 */
class Diac
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_student;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_group;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_teacher;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_course;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_period;

    /**
     * @ORM\Column(type="string", length=60, nullable = true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 60,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $observation;

    /**
     * @ORM\Column(type="date")
     */
    private $date;


    public function __construct()
    {

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
     * Set observation
     *
     * @param string $observation
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
    }

    /**
     * Get observation
     *
     * @return string 
     */
    public function getObservation()
    {
        return $this->observation;
    }

   /**
     * Set date
     *
     * @param date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return date 
     */
    public function getDate()
    {
        return $this->date;
    }

}