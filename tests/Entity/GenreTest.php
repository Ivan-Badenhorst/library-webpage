<?php
use App\Entity\BookGenre;
use App\Entity\Genre;
use App\Entity\UserGenre;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class GenreTest extends TestCase
{
    private $genre;
    private $userGenre;
    private $bookGenre;

    public function setUp(): void
    {
        $this->genre = new Genre();
        $this->userGenre = new UserGenre();
        $this->bookGenre = new BookGenre();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->genre->getId());
    }

    public function testGetAndSetGenre(): void
    {
        $this->assertNull($this->genre->getGenre());

        $this->genre->setGenre('Mystery');

        $this->assertSame('Mystery', $this->genre->getGenre());
    }

    public function testAddAndRemoveUserId(): void
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->genre->getGenreUserId());

        $this->genre->addUserId($this->userGenre);

        $this->assertTrue($this->genre->getGenreUserId()->contains($this->userGenre));
        $this->assertSame($this->genre, $this->userGenre->getGenreId());

        $this->genre->removeUserId($this->userGenre);

        $this->assertFalse($this->genre->getGenreUserId()->contains($this->userGenre));
        $this->assertNull($this->userGenre->getGenreId());
    }

    public function testAddAndRemoveGenreBookId(): void
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->genre->getGenreBookId());

        $this->genre->addGenreBookId($this->bookGenre);

        $this->assertTrue($this->genre->getGenreBookId()->contains($this->bookGenre));
        $this->assertSame($this->genre, $this->bookGenre->getGenreId());

        $this->genre->removeGenreBookId($this->bookGenre);

        $this->assertFalse($this->genre->getGenreBookId()->contains($this->bookGenre));
        $this->assertNull($this->bookGenre->getGenreId());
    }
}
