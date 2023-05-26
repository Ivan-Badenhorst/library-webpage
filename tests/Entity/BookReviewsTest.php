<?php


namespace App\Tests\Entity;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\BookReviews;
use PHPUnit\Framework\TestCase;

class BookReviewsTest extends TestCase
{
    public function testBookId()
    {
        $bookReviews = new BookReviews();
        $book = new Book();
        $bookReviews->setBookId($book);

        $this->assertEquals($book, $bookReviews->getBookId());
    }

    public function testUserId()
    {
        $bookReviews = new BookReviews();
        $user = new User();
        $bookReviews->setUserId($user);

        $this->assertEquals($user, $bookReviews->getUserId());
    }

    public function testScore()
    {
        $bookReviews = new BookReviews();
        $score = 5;
        $bookReviews->setScore($score);

        $this->assertEquals($score, $bookReviews->getScore());
    }

    public function testComment()
    {
        $bookReviews = new BookReviews();
        $comment = "This is a great book.";
        $bookReviews->setComment($comment);

        $this->assertEquals($comment, $bookReviews->getComment());
    }

    public function testDateAdded()
    {
        $bookReviews = new BookReviews();
        $dateAdded = new \DateTime();
        $bookReviews->setDateAdded($dateAdded);

        $this->assertEquals($dateAdded, $bookReviews->getDateAdded());
    }

    public function testId()
    {
        $bookReviews = new BookReviews();

        // Test the initial value of id
        $this->assertNull($bookReviews->getId());
    }
}
