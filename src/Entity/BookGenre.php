<?php
/**
 * @fileoverview Entity file for the BookGenre table in the database, contains definitions of its columns and methods to fetch/change their values.
 * This table in the database stores what genres a book fits into
 * @version 1.0
 */

/**
 * @author Aymeric Baume
 * @since 2023-04-28.
 */

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
    private ?Book $bookId = null;

    #[ORM\ManyToOne(inversedBy: 'genreBookId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genreId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?Book
    {
        return $this->bookId;
    }

    public function setBookId(?Book $bookId): self
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
