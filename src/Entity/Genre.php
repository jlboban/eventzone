<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 * @UniqueEntity(fields={"name"}, message="This genre is already created.")
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("genre_search")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("genre_search")
     */
    private ?string $name;

    /**
     * @ORM\ManyToMany(targetEntity=Musician::class, mappedBy="genre")
     */
    private Collection $musicians;

    public function __construct()
    {
        $this->musicians = new ArrayCollection();
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
            $musician->addGenre($this);
        }

        return $this;
    }

    public function removeMusician(Musician $musician): self
    {
        if ($this->musicians->contains($musician)) {
            $this->musicians->removeElement($musician);
            $musician->removeGenre($this);
        }

        return $this;
    }
}
