<?php

//This is the user entity: used to store information on users


namespace App\Entity;
use Symfony\Component\HttpFoundation\File\File;

use App\Repository\UserRepository;
use Cassandra\Blob;
use Doctrine\DBAL\Types\BlobType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column]
    private ?int $postalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?int $loginTries;

    #[ORM\Column(type: Types::BLOB, nullable: true)]

    private $profilePicture;


    public function getProfilePicture(): ?string
    {
        if($this->profilePicture == null){
            return "null";
        }
        return stream_get_contents($this->profilePicture);
    }

    public function setProfilePicture(?File $profilePicture): self
    {
        if ($profilePicture) {
            $this->profilePicture = file_get_contents($profilePicture);
        }

        return $this;
    }

    public function getLoginTries(): ?int
    {
        return $this->loginTries;
    }

    public function setLoginTries(?int $loginTries): self
    {
        $this->loginTries = $loginTries;
        return $this;
    }



    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: UserGenre::class, orphanRemoval: true)]
    private Collection $userGenreId;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: UserBook::class, orphanRemoval: true)]
    private Collection $userBookId;


    public function __construct()
    {
        $this->userGenreId = new ArrayCollection();
        $this->userBookId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, UserGenre>
     */
    public function getUserGenreId(): Collection
    {
        return $this->userGenreId;
    }

    public function addGenreId(UserGenre $genreId): self
    {
        if (!$this->userGenreId->contains($genreId)) {
            $this->userGenreId->add($genreId);
            $genreId->setUserId($this);
        }

        return $this;
    }

    public function removeGenreId(UserGenre $genreId): self
    {
        if ($this->userGenreId->removeElement($genreId)) {
            // set the owning side to null (unless already changed)
            if ($genreId->getUserId() === $this) {
                $genreId->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserBook>
     */
    public function getUserBookId(): Collection
    {
        return $this->userBookId;
    }

    public function addUserBookId(UserBook $userBookId): self
    {
        if (!$this->userBookId->contains($userBookId)) {
            $this->userBookId->add($userBookId);
            $userBookId->setUserId($this);
        }

        return $this;
    }

    public function removeUserBookId(UserBook $userBookId): self
    {
        if ($this->userBookId->removeElement($userBookId)) {
            // set the owning side to null (unless already changed)
            if ($userBookId->getUserId() === $this) {
                $userBookId->setUserId(null);
            }
        }

        return $this;
    }


}
