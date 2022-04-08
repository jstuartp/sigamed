<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Exception;
//use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Validator\Constraints as Assert;


/**
 *
 * @ORM\Table(name="tek_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 * @method string getUserIdentifier()
 */
class User implements UserInterface , \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 4,
     *      max = 25,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 150,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 150,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\Length(
     *      max = 32,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 40,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 60,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *
     * )
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     *
     */
    private $roles;

    /**
     * @var privileges
     *
     * @ORM\OneToMany(targetEntity="UserPrivilege", mappedBy="user", cascade={"persist", "remove"})
     */
    private $privileges;

    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function setEmail($email)
    {
        return $this->email = $email;
    }

    /**
     * @inheritDoc
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @inheritDoc
     */
    public function setActive($value)
    {
        return $this->isActive = $value;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function setUsername($username)
    {
        return $this->username = $username;
    }

    /**
     * @inheritDoc
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @inheritDoc
     */
    public function setFirstname($firstname)
    {
        return $this->firstname = $firstname;
    }
    /**
     * @inheritDoc
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @inheritDoc
     */
    public function setLastname($lastname)
    {
        return $this->lastname = $lastname;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function setPassword($value)
    {
        $this->salt = md5(time());
        $encoder = new MessageDigestPasswordEncoder('sha1', false, 1);
        $password = $encoder->encodePassword($value, $this->salt);
        $this->password = $password;
        return $this->password ;
    }

    /**
     * @inheritDoc
     *Devuelve unicamente los roles
     * en un array de strings para ser interpletado
     * por el security y hacer el login
     *
     */
    public function getRoles()
    {
/*
        $roles=[];
        $roles[] = "ROLE_SUPERADMIN";
        return $roles;
*/
        $rolesArray = [];

        foreach ($this->roles as $role) {
            $rolesArray[] = $role->getRole();
        }

        return $rolesArray;
        //return $this->roles->toArray();
        //return $this->roles->toArray();

    }
    public function getIdRoles()
    {
        /*
                $roles=[];
                $roles[] = "ROLE_SUPERADMIN";
                return $roles;
        */
        $rolesIdArray = [];

        foreach ($this->roles as $role) {
            $rolesIdArray[] = $role->getId();
        }

        return $rolesIdArray;
        //return $this->roles->toArray();
        //return $this->roles->toArray();

    }


    /**
     * Devuelve un objeto de la clas ROLE
     * con todos los datos
    */
    public function getRolesObjects() {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function getUserRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @inheritDoc
     */
    public function equals(UserInterface $user)
    {

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUserIdentifier()) {
            return false;
        }

        if ($this->email !== $user->getEmail()) {
            return false;
        }
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function __toString()
    {
        return $this->username;
    }

    public function getPrivileges(){
        return $this->privileges;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->salt,
            $this->password,
            $this->email,
        ]);

    }

    public function unserialize($string)
    {
        list(
            $this->id,
            $this->username,
            $this->salt,
            $this->password,
            $this->email,
            ) = unserialize($string,['allowed_clases'=> false]);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
}
?>