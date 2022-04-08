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
 * @ORM\Table(name="tek_users_privileges")
 * @ORM\Entity()
 */
class UserPrivilege
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
     * @ManyToOne(targetEntity="ActionMenu")
     * @JoinColumn(name="action_menu_id", referencedColumnName="id")
     */
    private $actionMenu;

    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->user . " - " . $this->actionMenu;
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
     * @param \Tecnotek\ExpedienteBundle\Entity\User $user
     */
    public function setUser(\Tecnotek\ExpedienteBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set actionMenu
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\ActionMenu $actionMenu
     */
    public function setActionMenu(\Tecnotek\ExpedienteBundle\Entity\ActionMenu $actionMenu)
    {
        $this->actionMenu = $actionMenu;
    }

    /**
     * Get actionMenu
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\ActionMenu
     */
    public function getActionMenu()
    {
        return $this->actionMenu;
    }

}