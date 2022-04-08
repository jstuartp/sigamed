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
 * @ORM\Table(name="tek_record_user")
 * @ORM\Entity()
 * @UniqueEntity("licensePlate")
 */
class RecordUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ManyToOne(targetEntity="Record")
     * @JoinColumn(name="record_id", referencedColumnName="id")
     */
    private $record;

    public function __construct()
    {
        $this->description = "";
    }

    public function __toString()
    {
        return $this->id;
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
     * Set user
     *
     * @param \App\Entity\User $user
     */
    public function setUser(\App\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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
}