<?php

namespace App\Tests\Panther;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Symfony\Component\Panther\PantherTestCase;

class BookInfoTest extends PantherTestCase
{

    public function testOpen(): void{
        //open without login:
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/');

        // Find the first div with ID "book-container"
        $div = $crawler->filter('#book-container')->first();

        // Get the URL of the link inside the div
        $linkUrl = 'http://127.0.0.1:9080/book-info/26';

        // Click on the div
        $div->click();

        // Assert the current URL after the click
        $this->assertSame($linkUrl, $client->getCurrentURL());


        //open with login:

        $crawler = $client->request('GET', '/');
        $logoutButton = $crawler->filter('a.normalButton')->first();
        $crawler = $client->click($logoutButton->link());

        $form = $crawler->filter('form[name="login"]')->form();
        $form['login[email]'] = 'newUser@email.com'; // Update with the desired email
        $form['login[password]'] = 'password'; // Update with the desired password

        $crawler = $client->submit($form);

        // Find the first div with ID "book-container"
        $div = $crawler->filter('#book-container')->first();

        // Get the URL of the link inside the div
        $linkUrl = 'http://127.0.0.1:9080/book-info/26';

        // Click on the div
        $div->click();

        // Assert the current URL after the click
        $this->assertSame($linkUrl, $client->getCurrentURL());

    }

    public function testOpen(): void{


    }





}