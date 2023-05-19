<?php

namespace App\Entity;

use App\Repository\BookGenreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookGenreRepository::class)]
class BookGenre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookGenreId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?book $bookId = null;

    #[ORM\ManyToOne(inversedBy: 'genreBookId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genreId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?book
    {
        return $this->bookId;
    }

    public function setBookId(?book $bookId): self
    {
        $this->bookId = $bookId;

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
