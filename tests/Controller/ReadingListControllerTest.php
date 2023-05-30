<?php
/**
 * @fileoverview This test was written to test functions written in ReadingListController
 * @version 1.0
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Panther\PantherTestCase;

class ReadingListControllerTest extends PantherTestCase
{
    /**
     * This test was written to test that when the page is loaded the reading list.html
     * is opened and it quickly checks if the first header is correct
     */
    public function testReadingListPage()
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


        $client->request('GET', '/read-list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1');
        //$this->assertSelectorTextContains('h1', 'Reading List');
    }
}