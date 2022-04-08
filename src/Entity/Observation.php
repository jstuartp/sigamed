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
 * @ORM\Table(name="tek_observations")
 * @ORM\Entity()
 * @UniqueEntity("licensePlate")
 */
class Observation
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
     * @ManyToOne(targetEntity="CourseClass")
     * @JoinColumn(name="course_class_id", referencedColumnName="id")
     */
    private $courseClass;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ManyToOne(targetEntity="Group")
     * @JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @ManyToOne(targetEntity="StudentYear")
     * @JoinColumn(name="student_year_id", referencedColumnName="id")
     */
    private $studentYear;

    public function __construct()
    {
        $this->detail = "";
    }

    public function __toString()
    {
        return $this->studentYear;
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
     * Set courseClass
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\CourseClass $courseClass
     */
    public function setCourseClass(\Tecnotek\ExpedienteBundle\Entity\CourseClass $courseClass)
    {
        $this->courseClass = $courseClass;
    }

    /**
     * Get courseClass
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\CourseClass
     */
    public function getCourseClass()
    {
        return $this->courseClass;
    }

    /**
     * Set group
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Group $group
     */
    public function setGroup(\Tecnotek\ExpedienteBundle\Entity\Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set studentYear
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\StudentYear $studentYear
     */
    public function setStudentYear(\Tecnotek\ExpedienteBundle\Entity\StudentYear $studentYear)
    {
        $this->studentYear = $studentYear;
    }

    /**
     * Get studentYear
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\StudentYear
     */
    public function getStudentYear()
    {
        return $this->studentYear;
    }

}