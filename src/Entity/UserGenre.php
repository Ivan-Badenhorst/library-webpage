<?php

namespace App\Entity;

use App\Repository\UserGenreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserGenreRepository::class)]
class UserGenre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: '$userGenreId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: '$genreUserId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genreId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getGenreId(): ?Genre
    {
        return $this->genreId;
    }

    public function setGenreId(?Genre $genreId): self
    {
        $this->genreId = $genreId;

        return $this;
    }

}
