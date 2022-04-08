<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_tek_role")
 * @ORM\Entity()
 */
class UserRole
{
    /**
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $user_id;

    /**
     * @ORM\Column(name="tek_role_id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $tek_role_id;



    public function __construct()
    {

    }

    // ... getters and setters for each property

    /**
     * @see RoleInterface
     */
    public function getRoleId()
    {
        return $this->tek_role_id;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
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
}
?>
