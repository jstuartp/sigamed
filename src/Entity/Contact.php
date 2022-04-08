<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * @ORM\Table(name="tek_contacts")
 * @ORM\Entity()
 * @UniqueEntity("identification")
 */
class Contact
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $identification;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $phonec;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $m_status;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $phonew;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $phoneh;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $workplace;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\Length(
     *      max = 120,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $nationality;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $payments_r;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      max = 120,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $restriction;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $religion;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      max = 60,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $occupation;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $birthday;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(
     *      min = 0,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
     * )
     */
    private $degree;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sector;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $soc;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $tipo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $relacion;


    public function __construct()
    {
        $this->identification = "";
        $this->degree = 0;
    }

    public function __toString()
    {
        return $this->firstname . " " . $this->lastname;
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
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set identificacion
     *
     * @param string $identification
     */
    public function setIdentification($identification)
    {
        $this->identification = $identification;
    }

    /**
     * Get identification
     *
     * @return string
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * Set phonec
     *
     * @param string $phonec
     */
    public function setPhonec($phonec)
    {
        $this->phonec = $phonec;
    }

    /**
     * Get phonec
     *
     * @return string
     */
    public function getPhonec()
    {
        return $this->phonec;
    }

    /**
     * Set m_status
     *
     * @param string $m_status
     */
    public function setMstatus($m_status)
    {
        $this->m_status = $m_status;
    }

    /**
     * Get m_status
     *
     * @return string
     */
    public function getMstatus()
    {
        return $this->m_status;
    }

    /**
     * Set phonew
     *
     * @param string $phonew
     */
    public function setPhonew($phonew)
    {
        $this->phonew = $phonew;
    }

    /**
     * Get phonew
     *
     * @return string
     */
    public function getPhonew()
    {
        return $this->phonew;
    }

    /**
     * Set phoneh
     *
     * @param string $phoneh
     */
    public function setPhoneh($phoneh)
    {
        $this->phoneh = $phoneh;
    }

    /**
     * Get phoneh
     *
     * @return string
     */
    public function getPhoneh()
    {
        return $this->phoneh;
    }

    /**
     * Set fax
     *
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set workplace
     *
     * @param string $workplace
     */
    public function setWorkplace($workplace)
    {
        $this->workplace = $workplace;
    }

    /**
     * Get workplace
     *
     * @return string
     */
    public function getWorkplace()
    {
        return $this->workplace;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set adress
     *
     * @param string $adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
    }

    /**
     * Get nationality
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set payments_r
     *
     * @param string $payments_r
     */
    public function setPayments_r($payments_r)
    {
        $this->payments_r = $payments_r;
    }

    /**
     * Get payments_r
     *
     * @return string
     */
    public function getPayments_r()
    {
        return $this->payments_r;
    }

    /**
     * Set restriction
     *
     * @param string $restriction
     */
    public function setRestriction($restriction)
    {
        $this->restriction = $restriction;
    }

    /**
     * Get restriction
     *
     * @return string
     */
    public function getRestriction()
    {
        return $this->restriction;
    }

    /**
     * Set religion
     *
     * @param string $religion
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;
    }

    /**
     * Get religion
     *
     * @return string
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * Set profession
     *
     * @param string $profession
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;
    }

    /**
     * Get profession
     *
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set occupation
     *
     * @param string $occupation
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;
    }

    /**
     * Get occupation
     *
     * @return string
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * Get birthday
     *
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set soc
     *
     * @param string $soc
     */
    public function setSoc($soc)
    {
        $this->soc = $soc;
    }

    /**
     * Get soc
     *
     * @return string
     */
    public function getSoc()
    {
        return $this->soc;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set relacion
     *
     * @param integer $relacion
     */
    public function setRelacion($relacion)
    {
        $this->relacion = $relacion;
    }

    /**
     * Get relacion
     *
     * @return integer
     */
    public function getRelacion()
    {
        return $this->relacion;
    }

    /**
     * Set degree
     *
     * @param integer $degree
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;
    }

    /**
     * Get degree
     *
     * @return integer
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set sector
     *
     * @param integer $sector
     */
    public function setSector($sector)
    {
        $this->sector = $sector;
    }

    /**
     * Get sector
     *
     * @return integer
     */
    public function getSector()
    {
        return $this->sector;
    }
}