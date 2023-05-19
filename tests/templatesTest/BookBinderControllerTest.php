<?php

namespace App\Tests\templatesTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookBinderControllerTest extends WebTestCase
{
    public function testBase(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Verify that the response was successful
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Verify that the HTML contains the expected stylesheets
        $this->assertSelectorExists('link[href="/css/base.css"]');
        $this->assertSelectorExists('link[href="/css/main.css"]');
    }

    public function testInfoBook(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/book-info');

        // Verify that the response was successful
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Verify that the HTML contains the expected stylesheets
        $this->assertSelectorExists('link[href="/css/base.css"]');
        $this->assertSelectorExists('link[href="/css/bookinfo.css"]');
    }

    public function testProfile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profile');

        // Verify that the response was successful
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Verify that the HTML contains the expected stylesheets
        $this->assertSelectorExists('link[href="/css/base.css"]');
        $this->assertSelectorExists('link[href="/css/profile.css"]');
    }


}
