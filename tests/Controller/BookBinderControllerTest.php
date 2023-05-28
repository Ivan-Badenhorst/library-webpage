<?php
/**
 * @fileoverview This test was written to test functions written in BookBinderController
 * @version 1.0
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class BookBinderControllerTest extends PantherTestCase
{
    public function testHome()
    {
        $client = static::createClient(); //this is used for static html so no js
        $crawler = $client->request('GET', '/');

        $this->assertStringContainsString('main.css', $client->getResponse()->getContent());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('div.favourite-text h1', 'OUR FAVORITES');
    }

    public function testUnderConstruction()
    {
        $client = static::createClient();
        $client->request('GET', '/underconstr');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


}
