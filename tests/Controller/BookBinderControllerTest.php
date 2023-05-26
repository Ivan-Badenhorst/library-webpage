<?php

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
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertStringContainsString('main.css', $client->getResponse()->getContent());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('div.favourite-text h1', 'OUR FAVORITES');

        //$client1 = static::createClient();
        //$client1->request('GET', '/');
        //$this->assertResponseIsSuccessful();
        //$this->assertSelectorTextContains('h1', 'Welcome to Book Binder');
        //$this->assertSelectorExists('genres');
        //$this->assertSelectorExists('.book-list');
    }

    public function testProfile()
    {
        $client = static::createClient();

        $client->request('GET', '/profile');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1.title');
        $this->assertSelectorTextContains('h1.title', 'Settings');
        $this->assertStringContainsString('profile.css', $client->getResponse()->getContent());


        // Add additional assertions as needed to test the content of the profile page

        //$this->assertSelectorExists('form[action="/profile"]');
        //$this->assertSelectorExists('form[action="/profile#personal-information"]');
        //$this->assertSelectorExists('form[action="/profile#preferences"]');
        //$this->assertSelectorExists('form[action="/profile#security"]');

        //$this->assertSelectorTextContains('h1', 'User Profile');
        //$this->assertSelectorExists('.profile-info');
    }

}
