<?php
namespace App\Tests\Entity;

use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
public function testGettersAndSetters()
{
$book = new Book();

// Test the Author getter and setter
$this->assertNull($book->getAuthor());
$book->setAuthor('John Doe');
$this->assertEquals('John Doe', $book->getAuthor());

// Test the Summary getter and setter
$this->assertNull($book->getSummary());
$book->setSummary('This is a summary');
$this->assertEquals('This is a summary', $book->getSummary());

// Test the Title getter and setter
$this->assertNull($book->getTitle());
$book->setTitle('Some Title');
$this->assertEquals('Some Title', $book->getTitle());

// Test the ISBN getter and setter
$this->assertNull($book->getIsbn());
$book->setIsbn('1234567890');
$this->assertEquals('1234567890', $book->getIsbn());

// Test the BookGenreId getter, adder, and remover
$this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $book->getBookGenreId());
$bookGenre = $this->createMock(\App\Entity\BookGenre::class);
$bookGenre->expects($this->once())->method('setBookId')->with($book);
$book->addBookGenreId($bookGenre);
$this->assertTrue($book->getBookGenreId()->contains($bookGenre));
$book->removeBookGenreId($bookGenre);
$this->assertFalse($book->getBookGenreId()->contains($bookGenre));

        // Test the BookUserId getter, adder, and remover
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $book->getBookUserId());
        $userBook = $this->createMock(\App\Entity\UserBook::class);
        $userBook->expects($this->once())->method('setBookId')->with($book);
        $book->addBookUserId($userBook);
        $this->assertTrue($book->getBookUserId()->contains($userBook));
        $book->removeBookUserId($userBook);
        $this->assertFalse($book->getBookUserId()->contains($userBook));

        // Test the initial value of bookCover
        $this->assertNull($book->getBookCover());

        // Test setting and getting the bookCover
        $book->setBookCover('book-cover.jpg');
        $this->assertEquals('book-cover.jpg', $book->getBookCover());
    }

    public function testId()
    {
        $book = new Book();

        // Test the initial value of id
        $this->assertNull($book->getId());
    }

    public function testBookReviewId()
    {
        $book = new Book();

        // Test the initial value of bookReviewId
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $book->getBookReviewId());
        $this->assertCount(0, $book->getBookReviewId());

        // Create a mock BookReviews entity
        $bookReview = $this->createMock(\App\Entity\BookReviews::class);
        $bookReview->expects($this->once())->method('setBookId')->with($book);

        // Add the bookReview to the book
        $book->addBookReviewId($bookReview);

        // Test that the bookReview was added
        $this->assertCount(1, $book->getBookReviewId());
        $this->assertTrue($book->getBookReviewId()->contains($bookReview));

        // Remove the bookReview from the book
        $book->removeBookReviewId($bookReview);

        // Test that the bookReview was removed
        $this->assertCount(0, $book->getBookReviewId());
        $this->assertFalse($book->getBookReviewId()->contains($bookReview));
    }

}
