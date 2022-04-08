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
 * @ORM\Table(name="tek_ticket_item")
 * @ORM\Entity()
 */
class TicketItem
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
     * @ManyToOne(targetEntity="Item")
     * @JoinColumn(name="item_id", referencedColumnName="id", nullable=true)
     */
    private $item;

    /**
     * @ManyToOne(targetEntity="Ticket")
     * @JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    private $ticket;


    public function __construct()
    {

    }

    public function __toString()
    {
        return "Prestamo #" . $this->id . ": " . $this->item. " :: " . $this->ticket;
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
     * Set ticket
     *
     * @param \App\Entity\Ticket $ticket
     */
    public function setTicket(\App\Entity\Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get ticket
     *
     * @return \App\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set item
     *
     * @param \App\Entity\Item $item
     */
    public function setItem(\App\Entity\Item $item)
    {
        $this->item = $item;
    }

    /**
     * Get item
     *
     * @return \App\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }


}