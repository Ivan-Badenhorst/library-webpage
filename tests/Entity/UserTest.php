<?php
/**
 * @fileoverview This test was written to test functions written in User entity
 * Tests are mostly self explanatory
 * @version 1.0
 */

/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\UserGenre;
use App\Entity\UserBook;
use App\Entity\BookReviews;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $user = new User();
        $email = 'john@example.com';
        $password = 'secret';
        $displayName = 'John';
        $firstName = 'John';
        $lastName = 'Doe';
        $dateOfBirth = new \DateTime();
        $street = '123 Main St';
        $postalCode = 12345;
        $city = 'Anytown';
        $loginTries = 3;
        $bio = 'This is a test.';

        // Test setters
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setDisplayName($displayName);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setDateOfBirth($dateOfBirth);
        $user->setStreet($street);
        $user->setPostalCode($postalCode);
        $user->setCity($city);
        $user->setLoginTries($loginTries);
        $user->setBio($bio);

        // Test getters
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($displayName, $user->getDisplayName());
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($dateOfBirth, $user->getDateOfBirth());
        $this->assertEquals($street, $user->getStreet());
        $this->assertEquals($postalCode, $user->getPostalCode());
        $this->assertEquals($city, $user->getCity());
        $this->assertEquals($loginTries, $user->getLoginTries());
        $this->assertEquals($bio, $user->getBio());
    }

    public function testUserGenres()
    {
        $user = new User();
        $genre = new UserGenre();

        // Test adding and removing genres
        $user->addGenreId($genre);
        $this->assertEquals(1, $user->getUserGenreId()->count());
        $user->removeGenreId($genre);
        $this->assertEquals(0, $user->getUserGenreId()->count());
    }

    public function testUserBooks()
    {
        $user = new User();
        $book = new UserBook();

        // Test adding and removing books
        $user->addUserBookId($book);
        $this->assertEquals(1, $user->getUserBookId()->count());
        $user->removeUserBookId($book);
        $this->assertEquals(0, $user->getUserBookId()->count());
    }

    public function testId()
    {
        $user = new User();

        // Test the initial value of id
        $this->assertNull($user->getId());
    }

    public function testProfilePicture()
    {
        $user = new User();

        // Test the initial value of profile picture
        $this->assertEquals("null", $user->getProfilePicture());
    }

}

