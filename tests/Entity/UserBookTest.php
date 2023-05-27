<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Book;
use App\Entity\UserBook;
use PHPUnit\Framework\TestCase;

/**
 * This test was written to test functions written in UserBook entity, tests are mostly self explanatory
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
class UserBookTest extends TestCase
{
    public function testGetId(): void
    {
        $userBook = new UserBook();
        $reflection = new \ReflectionClass($userBook);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($userBook, 1);
        $this->assertSame(1, $userBook->getId());
    }

    public function testGetUserId(): void
    {
        $user = new User();
        $userBook = new UserBook();
        $userBook->setUserId($user);
        $this->assertSame($user, $userBook->getUserId());
    }

    public function testSetUserId(): void
    {
        $user = new User();
        $userBook = new UserBook();
        $userBook->setUserId($user);
        $this->assertSame($user, $userBook->getUserId());
    }

    public function testGetBookId(): void
    {
        $book = new Book();
        $userBook = new UserBook();
        $userBook->setBookId($book);
        $this->assertSame($book, $userBook->getBookId());
    }

    public function testSetBookId(): void
    {
        $book = new Book();
        $userBook = new UserBook();
        $userBook->setBookId($book);
        $this->assertSame($book, $userBook->getBookId());
    }
}
