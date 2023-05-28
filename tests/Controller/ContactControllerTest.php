<?php

/**
 * @fileoverview This test was written to test functions written in ContactController
 * @version 1.0
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testContactPage()
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('label[for="name"]', 'Name: The Moonwalkers');
    }
}
