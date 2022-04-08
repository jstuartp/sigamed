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
 * @ORM\Table(name="tek_ticket")
 * @ORM\Entity()
 */
class Ticket
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo",
     *      maxMessage = "Usar menos de{{ limit }} caracteres para este campo"
     * )
     */
    private $comments;


    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $authorized;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $date_estimated;

    /**
     * @ORM\Column(type="time", nullable = true)
     */
    private $ticket_hour;

    /**
     * @ORM\Column(type="date", nullable = true)
     */
    private $ticket_date;


    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $date_in;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $date_out;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $date_update;


    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;


    /**
     * @ORM\Column(type="integer", nullable = true)
     * @Assert\Length(
     *      min = 1,
     *      minMessage = "Usar mas de {{ limit }} caracteres para este campo"
     * )
     */
    private $status;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_update_id", referencedColumnName="id")
     */
    private $user_update;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $computer;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ncomputer;

    /**
 * @ORM\Column(type="integer", nullable=true)
 */
    private $videobeam;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nvideobeam;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $camara;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ncamara;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $control;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hdmi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cable;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $recorder;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tripod;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $speaker;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $outlet;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $presenter;



    public function __construct()
    {

    }

    public function __toString()
    {
        return "Prestamo #" . $this->id . ": " . $this->item. " :: " . $this->student;
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
     * Set comments
     *
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set details
     *
     * @param string $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set authorized
     *
     * @param string $authorized
     */
    public function setAuthorized($authorized)
    {
        $this->authorized = $authorized;
    }

    /**
     * Get authorized
     *
     * @return string
     */
    public function getAuthorized()
    {
        return $this->authorized;
    }

    /**
     * Set date_in
     *
     * @param date $date_in
     */
    public function setDateIn($date_in)
    {
        $this->date_in = $date_in;
    }

    /**
     * Get date_in
     *
     * @return date
     */
    public function getDateIn()
    {
        return $this->date_in;
    }

    /**
     * Set date_out
     *
     * @param date $date_out
     */
    public function setDateOut($date_out)
    {
        $this->date_out = $date_out;
    }

    /**
     * Get date_out
     *
     * @return date
     */
    public function getDateOut()
    {
        return $this->date_out;
    }


    /**
     * Set date_estimated
     *
     * @param date $date_estimated
     */
    public function setDateEstimated($date_estimated)
    {
        $this->date_estimated = $date_estimated;
    }

    /**
     * Get date_estimated
     *
     * @return date
     */
    public function getDateEstimated()
    {
        return $this->date_estimated;
    }


    /**
     * Set ticket_hour
     *
     * @param time $ticket_hour
     */
    public function setTicketHour($ticket_hour)
    {
        $this->ticket_hour = $ticket_hour;
    }

    /**
     * Get ticket_hour
     *
     * @return time
     */
    public function getTicketHour()
    {
        return $this->ticket_hour;
    }



    /**
     * Set ticket_date
     *
     * @param date $ticket_date
     */
    public function setTicketDate($ticket_date)
    {
        $this->ticket_date = $ticket_date;
    }

    /**
     * Get ticket_date
     *
     * @return date
     */
    public function getTicketDate()
    {
        return $this->ticket_date;
    }


    /**
     * Set date_update
     *
     * @param date $date_update
     */
    public function setDateUpdate($date_update)
    {
        $this->date_update = $date_update;
    }

    /**
     * Get date_update
     *
     * @return date
     */
    public function getDateUpdate()
    {
        return $this->date_update;
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
     * Set user_update
     *
     * @param \App\Entity\User $user_update
     */
    public function setUserUpdate(\App\Entity\User $user_update)
    {
        $this->user_update = $user_update;
    }

    /**
     * Get user_update
     *
     * @return \App\Entity\User
     */
    public function getUserUpdate()
    {
        return $this->user_update;
    }


    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set computer
     *
     * @param integer $computer
     */
    public function setComputer($computer)
    {
        $this->computer = $computer;
    }

    /**
     * Get computer
     *
     * @return integer
     */
    public function getComputer()
    {
        return $this->computer;
    }

    /**
     * Set ncomputer
     *
     * @param string $ncomputer
     */
    public function setNcomputer($ncomputer)
    {
        $this->ncomputer = $ncomputer;
    }

    /**
     * Get ncomputer
     *
     * @return string
     */
    public function getNcomputer()
    {
        return $this->ncomputer;
    }

    /**
     * Set videobeam
     *
     * @param integer $videobeam
     */
    public function setVideobeam($videobeam)
    {
        $this->videobeam = $videobeam;
    }

    /**
     * Get videobeam
     *
     * @return integer
     */
    public function getVideobeam()
    {
        return $this->videobeam;
    }

    /**
     * Set nvideobeam
     *
     * @param string $nvideobeam
     */
    public function setNvideobeam($nvideobeam)
    {
        $this->nvideobeam = $nvideobeam;
    }

    /**
     * Get nvideobeam
     *
     * @return string
     */
    public function getNvideobeam()
    {
        return $this->nvideobeam;
    }

    /**
     * Set camara
     *
     * @param integer $camara
     */
    public function setCamara($camara)
    {
        $this->camara = $camara;
    }

    /**
     * Get camara
     *
     * @return integer
     */
    public function getCamara()
    {
        return $this->camara;
    }

    /**
     * Set ncamara
     *
     * @param string $ncamara
     */
    public function setNcamara($ncamara)
    {
        $this->ncamara = $ncamara;
    }

    /**
     * Get ncamara
     *
     * @return string
     */
    public function getNcamara()
    {
        return $this->ncamara;
    }

    /**
     * Set control
     *
     * @param integer $control
     */
    public function setControl($control)
    {
        $this->control = $control;
    }

    /**
     * Get control
     *
     * @return integer
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * Set hdmi
     *
     * @param integer $hdmi
     */
    public function setHdmi($hdmi)
    {
        $this->hdmi = $hdmi;
    }

    /**
     * Get hdmi
     *
     * @return integer
     */
    public function getHdmi()
    {
        return $this->hdmi;
    }

    /**
     * Set cable
     *
     * @param integer $cable
     */
    public function setCable($cable)
    {
        $this->cable = $cable;
    }

    /**
     * Get cable
     *
     * @return integer
     */
    public function getCable()
    {
        return $this->cable;
    }

    /**
     * Set recorder
     *
     * @param integer $recorder
     */
    public function setRecorder($recorder)
    {
        $this->recorder = $recorder;
    }

    /**
     * Get recorder
     *
     * @return integer
     */
    public function getRecorder()
    {
        return $this->recorder;
    }

    /**
     * Set tripod
     *
     * @param integer $tripod
     */
    public function setTripod($tripod)
    {
        $this->tripod = $tripod;
    }

    /**
     * Get tripod
     *
     * @return integer
     */
    public function getTripod()
    {
        return $this->tripod;
    }

    /**
     * Set speaker
     *
     * @param integer $speaker
     */
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;
    }

    /**
     * Get speaker
     *
     * @return integer
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * Set outlet
     *
     * @param integer $outlet
     */
    public function setOutlet($outlet)
    {
        $this->outlet = $outlet;
    }

    /**
     * Get outlet
     *
     * @return integer
     */
    public function getOutlet()
    {
        return $this->outlet;
    }

    /**
     * Set presenter
     *
     * @param integer $presenter
     */
    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * Get presenter
     *
     * @return integer
     */
    public function getPresenter()
    {
        return $this->presenter;
    }








}