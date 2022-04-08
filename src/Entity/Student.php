<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * @ORM\Table(name="tek_students")
 * @ORM\Entity()
 * @UniqueEntity("carne")
 */
class Student
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
    private $carne;

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
     * @ORM\Column(type="string", length=150, nullable=true))
     * @Assert\Length(
     *      max = 150,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $fatherPhone;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $motherPhone;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $pickUp;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $leaveTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dailyStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dailyDescription;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $gender;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $admission;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 25,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $identification;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $nacionality;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $religion;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 120,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $observation;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     * @Assert\Length(
     *      max = 75,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $laterality;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $payment;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rbautizado;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rtomo;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rfolio;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rasiento;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rpromesasfecha;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      max = 45,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rpromesaslugar;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rconfesionfecha;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      max = 45,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rconfesionlugar;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rcomunionfecha;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(
     *      max = 45,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $rcomunionlugar;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $groupyear;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $emergencyout;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $emergencyoutinst;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $brethren;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $familiars;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $emergencyinfo;

    /**
     * @ManyToOne(targetEntity="Route")
     * @JoinColumn(name="route_id", referencedColumnName="id", nullable=true)
     */
    private $route;

    /**
     * @ManyToOne(targetEntity="Route")
     * @JoinColumn(name="in_route_id", referencedColumnName="id", nullable=true)
     */
    private $routeIn;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $routeType;

    /**
     * @var ArrayCollection $clubs
     * @ORM\ManyToMany(targetEntity="Club", mappedBy="students")
     */
    private $clubs;

    /**
     * @var Relative
     *
     * @ORM\OneToMany(targetEntity="Relative", mappedBy="student", cascade={"all"})
     */
    private $relatives;


    public function __construct()
    {
        $this->clubs = new ArrayCollection();
        $this->dailyStatus = 0;
        $this->dailyDescription = "";
    }

    public function __toString()
    {

        return $this->lastname . " " . $this->firstname;
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
     * Set carne
     *
     * @param string $carne
     */
    public function setCarne($carne)
    {
        $this->carne = $carne;
    }

    /**
     * Get carne
     *
     * @return string
     */
    public function getCarne()
    {
        return $this->carne;
    }

    /**
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set fatherPhone
     *
     * @param string $fatherPhone
     */
    public function setFatherPhone($fatherPhone)
    {
        $this->fatherPhone = $fatherPhone;
    }

    /**
     * Get fatherPhone
     *
     * @return string
     */
    public function getFatherPhone()
    {
        return $this->fatherPhone;
    }

    /**
     * Set motherPhone
     *
     * @param string $motherPhone
     */
    public function setMotherPhone($motherPhone)
    {
        $this->motherPhone = $motherPhone;
    }

    /**
     * Get motherPhone
     *
     * @return string
     */
    public function getMotherPhone()
    {
        return $this->motherPhone;
    }

    /**
     * Set pickUp
     *
     * @param string $pickUp
     */
    public function setPickUp($pickUp)
    {
        $this->pickUp = $pickUp;
    }

    /**
     * Get pickUp
     *
     * @return string
     */
    public function getPickUp()
    {
        return $this->pickUp;
    }

    /**
     * Set leaveTime
     *
     * @param string $leaveTime
     */
    public function setLeaveTime($leaveTime)
    {
        $this->leaveTime = $leaveTime;
    }

    /**
     * Get leaveTime
     *
     * @return string
     */
    public function getLeaveTime()
    {
        return $this->leaveTime;
    }

    /**
     * Set dailyStatus
     *
     * @param integer $dailyStatus
     */
    public function setDailyStatus($dailyStatus)
    {
        $this->dailyStatus = $dailyStatus;
    }

    /**
     * Get dailyStatus
     *
     * @return integer
     */
    public function getDailyStatus()
    {
        return $this->dailyStatus;
    }

    /**
     * Set dailyDescription
     *
     * @param string $dailyDescription
     */
    public function setDailyDescription($dailyDescription)
    {
        $this->dailyDescription = $dailyDescription;
    }

    /**
     * Get dailyDescription
     *
     * @return string
     */
    public function getDailyDescription()
    {
        return $this->dailyDescription;
    }

    /**
     * @inheritDoc
     */
    public function getClubs()
    {
        return $this->clubs->toArray();
    }

    public function getRelatives(){
        return $this->relatives;
    }

    /**
     * Set route
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Route $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function removeRoute()
    {
        $this->route = null;
    }

    /**
     * Get route
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set routeIn
     *
     * @param \Tecnotek\ExpedienteBundle\Entity\Route $routeIn
     */
    public function setRouteIn($routeIn)
    {
        $this->routeIn = $routeIn;
    }

    public function removeRouteIn()
    {
        $this->routeIn = null;
    }

    /**
     * Get routeIn
     *
     * @return \Tecnotek\ExpedienteBundle\Entity\Route
     */
    public function getRouteIn()
    {
        return $this->routeIn;
    }

    /**
     * Set routeType
     *
     * @param integer $routeType
     */
    public function setRouteType($routeType)
    {
        $this->routeType = $routeType;
    }

    /**
     * Get routeType
     *
     * @return integer
     */
    public function getRouteType()
    {
        return $this->routeType;
    }


    /**
     * Set age
     *
     * @param integer $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set laterality
     *
     * @param integer $laterality
     */
    public function setLaterality($laterality)
    {
        $this->laterality = $laterality;
    }

    /**
     * Get laterality
     *
     * @return integer
     */
    public function getLaterality()
    {
        return $this->laterality;
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
     * Set admission
     *
     * @param string $admission
     */
    public function setAdmission($admission)
    {
        $this->admission = $admission;
    }

    /**
     * Get admission
     *
     * @return string
     */
    public function getAdmission()
    {
        return $this->admission;
    }

    /**
     * Set identification
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
     * Set city
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set nacionality
     *
     * @param string $nacionality
     */
    public function setNacionality($nacionality)
    {
        $this->nacionality = $nacionality;
    }

    /**
     * Get nacionality
     *
     * @return string
     */
    public function getNacionality()
    {
        return $this->nacionality;
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
     * Set observation
     *
     * @param string $observation
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
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
     * Set payment
     *
     * @param string $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get payment
     *
     * @return string
     */
    public function getPayment()
    {
        return $this->payment;
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
     * Set rbautizado
     *
     * @param string $rbautizado
     */
    public function setRbautizado($rbautizado)
    {
        $this->rbautizado = $rbautizado;
    }

    /**
     * Get rbautizado
     *
     * @return string
     */
    public function getRbautizado()
    {
        return $this->rbautizado;
    }

    /**
     * Set rtomo
     *
     * @param string $rtomo
     */
    public function setRtomo($rtomo)
    {
        $this->rtomo = $rtomo;
    }

    /**
     * Get rtomo
     *
     * @return string
     */
    public function getRtomo()
    {
        return $this->rtomo;
    }

    /**
     * Set rfolio
     *
     * @param string $rfolio
     */
    public function setRfolio($rfolio)
    {
        $this->rfolio = $rfolio;
    }

    /**
     * Get rfolio
     *
     * @return string
     */
    public function getRfolio()
    {
        return $this->rfolio;
    }

    /**
     * Set rasiento
     *
     * @param string $rasiento
     */
    public function setRasiento($rasiento)
    {
        $this->rasiento = $rasiento;
    }

    /**
     * Get rasiento
     *
     * @return string
     */
    public function getRasiento()
    {
        return $this->rasiento;
    }

    /**
     * Set rpromesasfecha
     *
     * @param string $rpromesasfecha
     */
    public function setRpromesasfecha($rpromesasfecha)
    {
        $this->rpromesasfecha = $rpromesasfecha;
    }

    /**
     * Get rpromesasfecha
     *
     * @return string
     */
    public function getRpromesasfecha()
    {
        return $this->rpromesasfecha;
    }

    /**
     * Set rpromesaslugar
     *
     * @param string $rpromesaslugar
     */
    public function setRpromesaslugar($rpromesaslugar)
    {
        $this->rpromesaslugar = $rpromesaslugar;
    }

    /**
     * Get rpromesaslugar
     *
     * @return string
     */
    public function getRpromesaslugar()
    {
        return $this->rpromesaslugar;
    }

    /**
     * Set rconfesionfecha
     *
     * @param string $rconfesionfecha
     */
    public function setRconfesionfecha($rconfesionfecha)
    {
        $this->rconfesionfecha = $rconfesionfecha;
    }

    /**
     * Get rconfesionfecha
     *
     * @return string
     */
    public function getRconfesionfecha()
    {
        return $this->rconfesionfecha;
    }

    /**
     * Set rconfesionlugar
     *
     * @param string $rconfesionlugar
     */
    public function setRconfesionlugar($rconfesionlugar)
    {
        $this->rconfesionlugar = $rconfesionlugar;
    }

    /**
     * Get rconfesionlugar
     *
     * @return string
     */
    public function getRconfesionlugar()
    {
        return $this->rconfesionlugar;
    }

    /**
     * Set rcomunionfecha
     *
     * @param string $rcomunionfecha
     */
    public function setRcomunionfecha($rcomunionfecha)
    {
        $this->rcomunionfecha = $rcomunionfecha;
    }

    /**
     * Get rcomunionfecha
     *
     * @return string
     */
    public function getRcomunionfecha()
    {
        return $this->rcomunionfecha;
    }

    /**
     * Set rcomunionlugar
     *
     * @param string $rcomunionlugar
     */
    public function setRcomunionlugar($rcomunionlugar)
    {
        $this->rcomunionlugar = $rcomunionlugar;
    }

    /**
     * Get rcomunionlugar
     *
     * @return string
     */
    public function getRcomunionlugar()
    {
        return $this->rcomunionlugar;
    }

    /**
     * Set groupyear
     *
     * @param string $groupyear
     */
    public function setGroupyear($groupyear)
    {
        $this->groupyear = $groupyear;
    }

    /**
     * Get groupyear
     *
     * @return string
     */
    public function getGroupyear()
    {
        return $this->groupyear;
    }

    /**
     * Set emergencyout
     *
     * @param integer $emergencyout
     */
    public function setEmergencyout($emergencyout)
    {
        $this->emergencyout = $emergencyout;
    }

    /**
     * Get emergencyout
     *
     * @return integer
     */
    public function getEmergencyout()
    {
        return $this->emergencyout;
    }

    /**
     * Set emergencyoutinst
     *
     * @param integer $emergencyoutinst
     */
    public function setEmergencyoutinst($emergencyoutinst)
    {
        $this->emergencyoutinst = $emergencyoutinst;
    }

    /**
     * Get emergencyoutinst
     *
     * @return integer
     */
    public function getEmergencyoutinst()
    {
        return $this->emergencyoutinst;
    }

    /**
     * Set brethren
     *
     * @param integer $brethren
     */
    public function setBrethren($brethren)
    {
        $this->brethren = $brethren;
    }

    /**
     * Get brethren
     *
     * @return integer
     */
    public function getBrethren()
    {
        return $this->brethren;
    }

    /**
     * Set familiars
     *
     * @param string $familiars
     */
    public function setFamiliars($familiars)
    {
        $this->familiars = $familiars;
    }

    /**
     * Get familiars
     *
     * @return string
     */
    public function getFamiliars()
    {
        return $this->familiars;
    }


    /**
     * Set emergencyinfo
     *
     * @param string $emergencyinfo
     */
    public function setEmergencyinfo($emergencyinfo)
    {
        $this->emergencyinfo = $emergencyinfo;
    }

    /**
     * Get emergencyinfo
     *
     * @return string
     */
    public function getEmergencyinfo()
    {
        return $this->emergencyinfo;
    }
}