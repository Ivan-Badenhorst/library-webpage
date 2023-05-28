<?php

namespace App\Tests\Panther;

use http\Client;
use Symfony\Component\Panther\PantherTestCase;

class ReadingListTest  extends PantherTestCase
{
    public function testOpen(): void{
        //if not logged in, reading list should redirect to login
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/logout');
        $crawler = $client->request('GET', '/read-list');



        // Assert the current URL after the click
        $linkUrl = 'http://127.0.0.1:9080/login';
        $this->assertSame($linkUrl, $client->getCurrentURL());



        $crawler = $client->refreshCrawler();
        //for reading list I first have to log in
        $form = $crawler->filter('form[name="login"]')->form();
        $form['login[email]'] = 'readingList@test.com'; // Update with the desired email
        $form['login[password]'] = 'password'; // Update with the desired password

        $crawler = $client->submit($form);


        $crawler = $client->request('GET', '/read-list');

        $screenshotPath = 'readinglist.png';
        $client->takeScreenshot($screenshotPath);
        $linkUrl = 'http://127.0.0.1:9080/read-list';
        $this->assertSame($linkUrl, $client->getCurrentURL());

        // Find the first <p> element within the <body>
        $firstParagraph = $crawler->filterXPath('//body//p')->first();

        // Get the text content of the first <p> element
        $paragraphText = $firstParagraph->text();

        // Assert the text content of the first <p> element
        $this->assertSame('There are no books in the reading list.', $paragraphText); // Replace with expected text


        //add 1 book
        $crawler = $client->request('GET', '/book-info/26');
        $button = $crawler->filter('#book_add_add_to_favorites')->first();
        $button->click();

        $crawler = $client->request('GET', '/read-list');
        $tbody = $crawler->filter('tbody')->first();
        $rowCount = $tbody->filter('tr')->count();

        $this->assertEquals(2, $rowCount); // Replace 5 with the expected row count

        //add 1 book
        $crawler = $client->request('GET', '/book-info/29');
        $button = $crawler->filter('#book_add_add_to_favorites')->first();
        $button->click();

        $crawler = $client->request('GET', '/read-list');
        $tbody = $crawler->filter('tbody')->first();
        $rowCount = $tbody->filter('tr')->count();

        $this->assertEquals(3, $rowCount); // Re


        //remove 1 book
        $crawler = $client->request('GET', '/book-info/26');
        $button = $crawler->filter('#book_add_add_to_favorites')->first();
        $button->click();

        $crawler = $client->request('GET', '/read-list');
        $tbody = $crawler->filter('tbody')->first();
        $rowCount = $tbody->filter('tr')->count();

        $this->assertEquals(2, $rowCount); // Re


        //remove 1 book
        $crawler = $client->request('GET', '/book-info/29');
        $button = $crawler->filter('#book_add_add_to_favorites')->first();
        $button->click();

        $crawler = $client->request('GET', '/read-list');
        $firstParagraph = $crawler->filterXPath('//body//p')->first();
        $paragraphText = $firstParagraph->text();
        $this->assertSame('There are no books in the reading list.', $paragraphText); // Replace with expected text


        $crawler = $client->request('GET', '/logout');
    }

}