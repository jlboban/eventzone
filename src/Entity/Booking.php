<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Event $event;

    /**
     * @ORM\Column(type="date")
     */
    private ?DateTimeInterface $order_date;

    /**
     * @ORM\Column(type="time")
     */
    private ?DateTimeInterface $order_time;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private ?string $final_price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getOrderDate(): ?DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(DateTimeInterface $order_date): self
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getOrderTime(): ?DateTimeInterface
    {
        return $this->order_time;
    }

    public function setOrderTime(DateTimeInterface $order_time): self
    {
        $this->order_time = $order_time;

        return $this;
    }

    public function getFinalPrice(): ?string
    {
        return $this->final_price;
    }

    public function setFinalPrice(string $final_price): self
    {
        $this->final_price = $final_price;

        return $this;
    }
}
