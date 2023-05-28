<?php
/**
 * @fileoverview This test was written to test functions written in BookInfoController
 * All tests are written in one function because you need to be logged and this takes the longest
 * This way, you only need to log in once
 * @version 1.0
 */

/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookInfoControllerTest extends WebTestCase
{
    public function testBookInfo()
    {
        $client = static::createClient();

        // Simulate logging in
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Submit')->form();
        $form['login[email]'] = "wout@example6.com";
        $form['login[password]'] = "12345678";
        $client->submit($form);
        $client->followRedirect();
        // Assert that the registration was successful
        $this->assertSame('/', $client->getRequest()->getPathInfo());

        // Simulate a request to the "/book-info/{bookId}" route
        $crawler = $client->request('GET', '/book-info/26');

        // Assert that the response is successful
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        // Assert that the page contains the expected content
        $this->assertSelectorTextContains('h1.book-title', 'The Hunger Games');

        /**
         * Here we test adding a book
         */

        // Simulate a POST request to the "/add/{bookId}" route
        $client->request('POST', '/add/26');

        // Assert that the response is successful
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        // Assert that the response is JSON and contains the expected data
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($response);

        /**
         * Here we test adding a review to a book and giving it a score
         */

        // Simulate a POST request to the "/write/{bookId}/{score}/{comment}" route
        $client->request('POST', '/write/26/4/This is a great book');

        // Assert that the response is successful
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        // Assert that the response is JSON and contains the expected data
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($response);

        /**
         * Here we test if we can get the review
         */

        // Simulate a GET request to the "/review/{bookId}/{offset}" route
        $client->request('GET', '/review/26/0');

        // Assert that the response is successful
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        // Assert that the response is JSON and contains the expected data
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('comment', $response[0]);
        $this->assertArrayHasKey('score', $response[0]);
        $this->assertArrayHasKey('date_added', $response[0]);
        $this->assertArrayHasKey('display_name', $response[0]);
    }
}
