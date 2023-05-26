<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

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
        $form['login[email]'] = "newUser@email.com";
        $form['login[password]'] = "password";
        $client->submit($form);
        $client->followRedirect();
        // Assert that the registration was successful
        $this->assertSame('/', $client->getRequest()->getPathInfo());
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
        $datetime = new \DateTime('2023-05-25');
        $datetimeString = $datetime->format('Y-m-d');
        $form['register[DateOfBirth]']->setValue($datetimeString);
        $form['register[street]'] = 'street';
        $form['register[postalCode]']->setValue(1234);
        $form['register[city]'] = 'city';
        dump($form);
        $client->submit($form);
        $client->followRedirect();
        // Assert that the registration was successful
        $this->assertSame('/', $client->getRequest()->getPathInfo());
    }
}
