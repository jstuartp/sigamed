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
 * @ORM\Table(name="tek_record_attachment")
 * @ORM\Entity()
 * @UniqueEntity("detail")
 */
class RecordAttachment
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
    private $type;

    /**
     * @ORM\Column(type="string", length=100, nullable = true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $filename;

    /**
     * @ORM\Column(name="place", type="string", length=45)
     */
    private $extension;

    /**
     * @ORM\Column(name="route", type="string", length=120)
     * @Assert\NotBlank()
     */
    private $route;

    /**
     * @ManyToOne(targetEntity="Record")
     * @JoinColumn(name="record_id", referencedColumnName="id", nullable=true)
     */
    private $record;

    public function __construct()
    {
        $this->filename = "";
    }

    public function __toString()
    {
        return $this->filename;
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
     * Set filename
     *
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
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
     * Set record
     *
     * @param \App\Entity\Record $record
     */
    public function setRecord(\App\Entity\Record $record)
    {
        $this->record = $record;
    }

    /**
     * Get record
     *
     * @return \App\Entity\Record
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * Set route
     *
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set extension
     *
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

}