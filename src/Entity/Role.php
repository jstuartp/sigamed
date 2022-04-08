<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @ORM\Table(name="tek_roles")
 * @ORM\Entity()
 */
class Role
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="roles")
     */
    private $users;

    public function __construct()
    {
        //$this->users = new ArrayCollection();
    }

    // ... getters and setters for each property

    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeRole($this);
        }

        return $this;
    }

    public function __toString() {
        return $this->role;
    }

}
?>
