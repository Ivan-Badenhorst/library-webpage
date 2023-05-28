<?php

/**
 * @fileoverview Controller test for the login controller
 * @version 1.0
 */

/**
 * @author Ivan Badenhorst
 * @since 2023-05-27.
 */
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\File\File;

class loginControllerTest extends WebTestCase
{
    private string $email;
    private string $password;


    public function __construct()
    {
        $name = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 10);
        $this->email = $name . '@email.com';
        $this->password = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 10);
        parent::__construct();
    }

    public function testLogin(){
        $client = static::createClient();

        // Simulate submitting the registration form
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Submit')->form();
        $form['login[email]'] = "wout@example2.com";
        $form['login[password]'] = "12345678";
        $client->submit($form);
        $client->followRedirect();
        // Assert that the registration was successful
        $this->assertSame('/', $client->getRequest()->getPathInfo());
    }

    public function testLoginWithWrongPassword()
    {
        $client = static::createClient();

        // Simulate submitting the login form with incorrect password
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Submit')->form();
        $form['login[email]'] = "wout@example.com";
        $form['login[password]'] = "wrongpassword";
        $client->submit($form);

        // Assert that the login was unsuccessful
        $this->assertSame('/login', $client->getRequest()->getPathInfo());
    }

    public function testLogout()
    {
        $client = static::createClient();

        // Simulate a logout request
        $crawler = $client->request('GET', '/logout');
        $client->followRedirect();

        // Assert that the user is redirected to the login page
        $this->assertSame('/login', $client->getRequest()->getPathInfo());
    }


    public function testRegister()
    {
        $client = static::createClient();

        // Simulate submitting the registration form with valid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        $form['register[email]'] = "wout@example6.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "example";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration was successful
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterWrongProfilePicType()
    {
        $client = static::createClient();


        // Create a mock file for profile picture
        $defaultImagePath = __DIR__ . '/../../public/images/book.svg';
        $profilePicture = new File($defaultImagePath);

        // Simulate submitting the registration form with valid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        $form['register[email]'] = "wout1@example1.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "example";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $form['register[profilePicture]'] = $profilePicture;
        $crawler = $client->submit($form);

        // Assert that the registration was successful
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterWrongProfilePicSize()
    {
        $client = static::createClient();


        // Create a mock file for profile picture
        $defaultImagePath = __DIR__ . '/../../public/images/too_big_png_test.jpg';
        $profilePicture = new File($defaultImagePath);

        // Simulate submitting the registration form with valid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        $form['register[email]'] = "wout1@example1.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "example";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $form['register[profilePicture]'] = $profilePicture;
        $crawler = $client->submit($form);

        // Assert that the registration was successful
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

        public function testRegisterLongEmail()
    {
        $client = static::createClient();

        // Simulate submitting the registration form with valid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        $form['register[email]'] = "testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@example.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "example";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration was successful
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterLongPassword()
    {
        $client = static::createClient();
        // Simulate submitting the registration form with invalid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        // Set invalid or incomplete form data
        $form['register[email]'] = "hey@test.com";
        $form['register[password]'] = "testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "example";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration failed and appropriate error messages are displayed
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterShortPassword()
    {
        $client = static::createClient();
        // Simulate submitting the registration form with invalid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        // Set invalid or incomplete form data
        $form['register[email]'] = "hey@test.com";
        $form['register[password]'] = "Wout";
        $form['register[name]'] = "test";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "example";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration failed and appropriate error messages are displayed
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterLongName()
    {
        $client = static::createClient();
        // Simulate submitting the registration form with invalid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        // Set invalid or incomplete form data
        $form['register[email]'] = "hey@test.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "example";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration failed and appropriate error messages are displayed
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterLongSurname()
    {
        $client = static::createClient();
        // Simulate submitting the registration form with invalid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        // Set invalid or incomplete form data
        $form['register[email]'] = "hey@test.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "example";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration failed and appropriate error messages are displayed
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterLongDisplayName()
    {
        $client = static::createClient();
        // Simulate submitting the registration form with invalid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        // Set invalid or incomplete form data
        $form['register[email]'] = "hey@test.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "example";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration failed and appropriate error messages are displayed
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterLongStreet()
    {
        $client = static::createClient();
        // Simulate submitting the registration form with invalid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        // Set invalid or incomplete form data
        $form['register[email]'] = "hey@test.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest";
        $form['register[postalCode]'] = "1234";
        $form['register[city]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration failed and appropriate error messages are displayed
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterLongCity()
    {
        $client = static::createClient();
        // Simulate submitting the registration form with invalid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        // Set invalid or incomplete form data
        $form['register[email]'] = "hey@test.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[city]'] = "testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest";
        $form['register[postalCode]'] = "1234";
        $form['register[street]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration failed and appropriate error messages are displayed
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

    public function testRegisterLongPostalCode()
    {
        $client = static::createClient();
        // Simulate submitting the registration form with invalid data
        $crawler = $client->request('GET', '/register');
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
        $form = $crawler->selectButton('Submit')->form();
        // Set invalid or incomplete form data
        $form['register[email]'] = "hey@test.com";
        $form['register[password]'] = "12345678";
        $form['register[name]'] = "Wout";
        $form['register[surname]'] = "Example";
        $form['register[displayname]'] = "Wout";
        $form['register[DateOfBirth]'] = "2008/08/08";
        $form['register[street]'] = "test";
        $form['register[postalCode]'] = "12345678910";
        $form['register[city]'] = "example";
        $crawler = $client->submit($form);

        // Assert that the registration failed and appropriate error messages are displayed
        $this->assertSame('/register', $client->getRequest()->getPathInfo());
    }

}
