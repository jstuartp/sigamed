<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="tek_zones")
 * @ORM\Entity()
 */
class Zone
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
     *      max = 60,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=120, nullable = true)
     * @Assert\Length(
     *      max = 120,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $description;

    /**
     * @var Routes
     *
     * @ORM\OneToMany(targetEntity="Route", mappedBy="zone")
     */
    private $routes;

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
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function getRoutes(){
        return $this->routes;
    }
}