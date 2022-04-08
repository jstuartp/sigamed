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
 * @ORM\Table(name="tek_questionnaire_groups")
 * @ORM\Entity()
 * @UniqueEntity("name")
 */
class QuestionnaireGroup
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 120,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $name;

    /**
     * @ORM\Column(name="enable_for_teacher", type="boolean")
     */
    private $enableForTeacher;

    /**
     * @var questionnaires
     *
     * @ORM\OneToMany(targetEntity="Questionnaire", mappedBy="group", cascade={"all"})
     * @ORM\OrderBy({"sortOrder" = "ASC"})
     */
    private $questionnaires;

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

    public function isEnabledForTeacher()
    {
        return $this->enableForTeacher;
    }

    public function getQuestionnaires(){
        return $this->questionnaires;
    }
}