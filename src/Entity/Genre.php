<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\OneToMany(mappedBy: 'genreId', targetEntity: UserGenre::class, orphanRemoval: true)]
    private Collection $genreUserId;

    public function __construct()
    {
        $this->genreUserId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, UserGenre>
     */
    public function getGenreUserId(): Collection
    {
        return $this->genreUserId;
    }

    public function addUserId(UserGenre $userId): self
    {
        if (!$this->genreUserId->contains($userId)) {
            $this->genreUserId->add($userId);
            $userId->setGenreId($this);
        }

        return $this;
    }

    public function removeUserId(UserGenre $userId): self
    {
        if ($this->genreUserId->removeElement($userId)) {
            // set the owning side to null (unless already changed)
            if ($userId->getGenreId() === $this) {
                $userId->setGenreId(null);
            }
        }

        return $this;
    }
}
