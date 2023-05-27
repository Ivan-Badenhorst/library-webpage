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
        $client = static::createClient(); //this is used for static html so no js
        $crawler = $client->request('GET', '/');

        $this->assertStringContainsString('main.css', $client->getResponse()->getContent());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('div.favourite-text h1', 'OUR FAVORITES');


    }

    /*public function testReadingList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/read-list');

        //$this->assertStringContainsString('readingList.css', $client->getResponse()->getContent());
        $this->assertResponseIsSuccessful();
        //$this->assertSelectorExists('h1');
    }*/

    /*public function testProfile()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profile');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="personal_info"]');
        $this->assertSelectorExists('form[name="preferences"]');
        $this->assertSelectorExists('form[name="security_privacy"]');
        // Add more assertions as needed to test the presence of specific elements

        // Simulate form submission for personal_info form
        $client->submitForm('Save', [
            'personal_info[checkbox1]' => true,
            'personal_info[checkbox2]' => false,
            'personal_info[checkbox3]' => true,
            // Add more form fields if needed
        ]);

        // Assert the response after form submission
        $this->assertTrue($client->getResponse()->isRedirect('/profile'));

        // Simulate form submission for preferences form
        $client->submitForm('Save', [
            'preferences[checkbox1]' => false,
            'preferences[checkbox2]' => true,
            'preferences[checkbox3]' => false,
            // Add more form fields if needed
        ]);

        // Assert the response after form submission
        $this->assertTrue($client->getResponse()->isRedirect('/profile'));

        // Simulate form submission for security_privacy form
        $client->submitForm('Save', [
            'security_privacy[checkbox1]' => true,
            'security_privacy[checkbox2]' => true,
            'security_privacy[checkbox3]' => false,
            // Add more form fields if needed
        ]);

        // Assert the response after form submission
        $this->assertTrue($client->getResponse()->isRedirect('/profile'));

        // Add more assertions to test the updated state of the profile or any other desired behavior
    }*/
}
