<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

    public function testRegister()
    {
        $client = static::createClient();

        // Simulate submitting the registration form
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Submit')->form();
        $form['register[email]'] = $this->email;
        $form['register[password]'] = $this->password;
        $form['register[name]'] = 'name';
        $form['register[surname]'] = 'surname';
        $form['register[displayname]'] = 'displayname';
        $form['register[DateOfBirth]'] = '2000-01-01';
        $form['register[street]'] = 'street';
        $form['register[postalCode]'] = '1234AB';
        $form['register[city]'] = 'city';
        $client->submit($form);

        // Assert that the registration was successful
        $this->assertTrue($client->getResponse()->isRedirect('/home'));
    }

    public function testLoginWhenLoggedIn()
    {
        $client = static::createClient();

        // Simulate submitting the login form
        $crawler = $client->request('GET', '/login');

        $this->assertSame('/home', $client->getRequest()->getPathInfo());
    }

    public function testLogout()
    {
        $client = static::createClient();

        // Simulate visiting the logout page
        $client->request('GET', '/logout');

        // Assert that the logout was successful
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }
    public function testLogin()
    {
        $client = static::createClient();

        // Simulate submitting the login form
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Submit')->form();
        $form['login[email]'] = $this->email;
        $form['login[password]'] = $this->password;
        $client->submit($form);

        // Assert that the login was successful
        $this->assertTrue($client->getResponse()->isRedirect('/home'));
    }
}
