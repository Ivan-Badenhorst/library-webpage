<?php
/**
 * @fileoverview Entity file for the Book table in the database, contains definitions of its columns and methods to fetch/change their values.
 * This table in the database stores the books and their relevant information.
 * @version 1.0
 */

/**
 * @author Aymeric Baume
 * @since 2023-04-28.
 */

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Author = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Summary = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column(length: 255)]
    #[Assert\Unique]
    private ?string $isbn = null;

    #[ORM\OneToMany(mappedBy: 'bookId', targetEntity: BookGenre::class, orphanRemoval: true)]
    private Collection $bookGenreId;

    #[ORM\OneToMany(mappedBy: 'bookId', targetEntity: UserBook::class, orphanRemoval: true)]
    private Collection $bookUserId;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bookCover = null;

    #[ORM\OneToMany(mappedBy: 'bookId', targetEntity: BookReviews::class, orphanRemoval: true)]
    private Collection $bookReviewId;


    public function __construct()
    {
        $this->bookGenreId = new ArrayCollection();
        $this->bookUserId = new ArrayCollection();
        $this->bookReviewId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->Summary;
    }

    public function setSummary(?string $Summary): self
    {
        $this->Summary = $Summary;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @return Collection<int, BookGenre>
     */
    public function getBookGenreId(): Collection
    {
        return $this->bookGenreId;
    }

    public function addBookGenreId(BookGenre $bookGenreId): self
    {
        if (!$this->bookGenreId->contains($bookGenreId)) {
            $this->bookGenreId->add($bookGenreId);
            $bookGenreId->setBookId($this);
        }

        return $this;
    }

    public function removeBookGenreId(BookGenre $bookGenreId): self
    {
        if ($this->bookGenreId->removeElement($bookGenreId)) {
            // set the owning side to null (unless already changed)
            if ($bookGenreId->getBookId() === $this) {
                $bookGenreId->setBookId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserBook>
     */
    public function getBookUserId(): Collection
    {
        return $this->bookUserId;
    }

    public function addBookUserId(UserBook $bookUserId): self
    {
        if (!$this->bookUserId->contains($bookUserId)) {
            $this->bookUserId->add($bookUserId);
            $bookUserId->setBookId($this);
        }

        return $this;
    }

    public function removeBookUserId(UserBook $bookUserId): self
    {
        if ($this->bookUserId->removeElement($bookUserId)) {
            // set the owning side to null (unless already changed)
            if ($bookUserId->getBookId() === $this) {
                $bookUserId->setBookId(null);
            }
        }

        return $this;
    }

    public function getBookCover(): ?string
    {
        return $this->bookCover;
    }

    public function setBookCover(?string $bookCover): self
    {
        $this->bookCover = $bookCover;

        return $this;
    }

    /**
     * @return Collection<int, BookReviews>
     */
    public function getBookReviewId(): Collection
    {
        return $this->bookReviewId;
    }

    public function addBookReviewId(BookReviews $bookReviewId): self
    {
        if (!$this->bookReviewId->contains($bookReviewId)) {
            $this->bookReviewId->add($bookReviewId);
            $bookReviewId->setBookId($this);
        }

        return $this;
    }

    public function removeBookReviewId(BookReviews $bookReviewId): self
    {
        if ($this->bookReviewId->removeElement($bookReviewId)) {
            // set the owning side to null (unless already changed)
            if ($bookReviewId->getBookId() === $this) {
                $bookReviewId->setBookId(null);
            }
        }

        return $this;
    }
}
