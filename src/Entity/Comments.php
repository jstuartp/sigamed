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
 * @ORM\Table(name="tek_comments")
 * @ORM\Entity()
 * @UniqueEntity("licensePlate")
 */
class Comments
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(
     *      min = 1,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
     * )
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $detail;


    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $date;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->detail = "";
    }

    public function __toString()
    {
        return $this->detail;
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
     * Set detail
     *
     * @param string $detail
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set teacher
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\User $teacher
     */
    public function setTeacher(\Tecnotek\ExpedienteBundle\Entity\User $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Get teacher
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\User
     */
    public function getTeacher()
    {
        return $this->teacher->getLastname(). ' ' . $this->teacher->getFirstname();
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