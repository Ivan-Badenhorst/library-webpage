<?php

namespace App\Entity;

use App\Repository\UserBookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserBookRepository::class)]
class UserBook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userBookId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $userId = null;

    #[ORM\ManyToOne(inversedBy: 'bookUserId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?book $bookId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?user
    {
        return $this->userId;
    }

    public function setUserId(?user $userId): self
    {
        $this->userId = $userId;

        return $this;
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
}
