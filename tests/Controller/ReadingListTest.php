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

use Symfony\Component\Panther\PantherTestCase;



class ReadingListTest extends PantherTestCase
{
    public function testReadListRedirectsToLoginIfNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/read-list');

        $this->assertResponseRedirects('/login');
    }

    /*public function testReadListDisplaysReadingListIfLoggedIn()
    {
        $client = static::createPantherClient();
        // Simulate a logged-in user by setting a session value
        $client->getContainer()->get('session')->set('email', 'user@example.com');

        $client->request('GET', '/read-list');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Reading List');
    }*/
}
