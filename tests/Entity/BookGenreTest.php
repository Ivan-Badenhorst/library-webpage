<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use App\Entity\BookGenre;
use App\Entity\Genre;
use PHPUnit\Framework\TestCase;

/**
 * This test was written to test functions written in BookGenre entity, tests are mostly self explanatory
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */

class BookGenreTest extends TestCase
{
    public function testBookId()
    {
        $bookGenre = new BookGenre();
        $book = new Book();
        $bookGenre->setBookId($book);

        $this->assertEquals($book, $bookGenre->getBookId());
    }

    public function testGenreId()
    {
        $bookGenre = new BookGenre();
        $genre = new Genre();
        $bookGenre->setGenreId($genre);

        $this->assertEquals($genre, $bookGenre->getGenreId());
    }

    public function testId()
    {
        $bookGenre = new BookGenre();

        // Test the initial value of id
        $this->assertNull($bookGenre->getId());
    }

}