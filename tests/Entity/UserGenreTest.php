<?php
/**
 * @fileoverview This test was written to test functions written in UserGenre entity
 * Tests are mostly self explanatory
 * @version 1.0
 */

/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */

namespace App\Tests\Entity;

use App\Entity\Genre;
use App\Entity\User;
use App\Entity\UserGenre;
use PHPUnit\Framework\TestCase;

class UserGenreTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $userGenre = new UserGenre();
        $user = new User();
        $genre = new Genre();

        $userGenre->setUserId($user);
        $userGenre->setGenreId($genre);

        $this->assertEquals(null, $userGenre->getId());
        $this->assertEquals($user, $userGenre->getUserId());
        $this->assertEquals($genre, $userGenre->getGenreId());
    }
}
