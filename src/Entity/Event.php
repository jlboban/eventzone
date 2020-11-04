<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private ?string $description;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private ?DateTimeInterface $start_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTimeInterface $end_date;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank
     */
    private ?DateTimeInterface $start_time;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private ?DateTimeInterface $end_time;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\NotBlank
     */
    private ?string $price;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    private ?string $discount;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Image(maxSize="100k", groups = {"create"})
     */
    private ?string $image;

    /**
     * @ORM\ManyToMany(targetEntity=Musician::class, inversedBy="events")
     * @Assert\NotBlank
     */
    private Collection $musicians;

    /**
     * @ORM\ManyToMany(targetEntity=Venue::class, inversedBy="events")
     * @Assert\NotBlank
     */
    private Collection $venues;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="event", orphanRemoval=true)
     */
    private Collection $bookings;

    /**
     * @ORM\Column(type="integer", options={"default":"365"})
     * @Assert\Range(
     *     min="0",
     *     max="999",
     *     notInRangeMessage="Invalid number of days."
     * )
     */
    private ?int $discount_begin;

    /**
     * @ORM\Column(type="integer", options={"default":"30"})
     * @Assert\Range(
     *     min="0",
     *     max="999",
     *     notInRangeMessage="Invalid number of days."
     * )
     */
    private ?int $discount_end;

    public function __construct()
    {
        $this->musicians = new ArrayCollection();
        $this->venues = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getStartTime(): ?DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(?DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(?string $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Musician[]
     */
    public function getMusicians(): Collection
    {
        return $this->musicians;
    }

    public function addMusician(Musician $musician): self
    {
        if (!$this->musicians->contains($musician)) {
            $this->musicians[] = $musician;
        }

        return $this;
    }

    public function removeMusician(Musician $musician): self
    {
        if ($this->musicians->contains($musician)) {
            $this->musicians->removeElement($musician);
        }

        return $this;
    }

    /**
     * @return Collection|Venue[]
     */
    public function getVenues(): Collection
    {
        return $this->venues;
    }

    public function addVenue(Venue $venue): self
    {
        if (!$this->venues->contains($venue)) {
            $this->venues[] = $venue;
        }

        return $this;
    }

    public function removeVenue(Venue $venue): self
    {
        if ($this->venues->contains($venue)) {
            $this->venues->removeElement($venue);
        }

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setEvent($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getEvent() === $this) {
                $booking->setEvent(null);
            }
        }

        return $this;
    }

    public function getDiscountBegin(): ?int
    {
        return $this->discount_begin;
    }

    public function setDiscountBegin(int $discount_begin): self
    {
        $this->discount_begin = $discount_begin;

        return $this;
    }

    public function getDiscountEnd(): ?int
    {
        return $this->discount_end;
    }

    public function setDiscountEnd(int $discount_end): self
    {
        $this->discount_end = $discount_end;

        return $this;
    }
}
