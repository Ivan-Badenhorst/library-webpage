<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use App\Entity\BookGenre;
use App\Entity\Genre;
use PHPUnit\Framework\TestCase;

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
}