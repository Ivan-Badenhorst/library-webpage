<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Panther\PantherTestCase;

/**
 * This test was written to test functions written in AboutController
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
class AboutControllerTest extends PantherTestCase
{
    /**
     * This test was written to test that when the page is loaded the about.html
     * is opened and it quickly checks if the first header is correct
     */
    public function testAboutPage()
    {
        $client = static::createClient();

        $client->request('GET', '/about');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('h1.book-title', 'About Us');
    }
}
