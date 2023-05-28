<?php

/**
 * @fileoverview This test was written to test functions written in ProfileController
 * @version 1.0
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileControllerTest extends WebTestCase
{
    /**
     * the test first logs in so that its able to go to the profile page
     * Afterwards both the forms are filled in where data is changed to see if the data actualy gets changed
     */
    public function testProfile()
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

        // Make a request to the profile page
        $crawler = $client->request('GET', '/profile');

        // Assert that the response is successful
        $this->assertResponseIsSuccessful();

        // Create a mock file for profile picture
        $defaultImagePath = __DIR__ . '/../../public/images/book.svg';
        $profilePicture = new File($defaultImagePath);

        // Fill and submit the personal info form
        $form = $crawler->filter('form[name="personal_info"]')->form();
        $form['personal_info[firstName]'] = "Jef";
        $form['personal_info[lastName]'] = "Example";
        $form['personal_info[dateOfBirth]'] = "2008-08-08";
        $form['personal_info[displayName]'] = "Wout";
        $form['personal_info[email]'] = "wout@example6.com";
        $form['personal_info[bio]'] = "Example";
        $form['personal_info[street]'] = "Example";
        $form['personal_info[postalCode]'] = "1234";
        $form['personal_info[city]'] = "Example";
        $form['personal_info[profilePicture]'] = $profilePicture;
        $client->submit($form);

        // Assert that the form submission was successful
        $this->assertResponseIsSuccessful();

        // Assert that the profile information is updated correctly
        $this->assertSelectorTextContains('label[for="firstName"]', 'First Name: Jef');
        $this->assertSelectorTextContains('label[for="lastName"]', 'Last Name: Example');

        // Fill and submit the security and privacy form
        $form = $crawler->filter('form[name="security_privacy"]')->form();
        $form['security_privacy[password]'] = "12345678";
        // ... Fill in the form fields
        $client->submit($form);

        // Assert that the form submission was successful
        $this->assertResponseIsSuccessful();

        // Simulate a logout request
        $crawler = $client->request('GET', '/logout');
        $client->followRedirect();

        // Assert that the user is redirected to the login page
        $this->assertSame('/login', $client->getRequest()->getPathInfo());

        // Log in again
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Submit')->form();
        $form['login[email]'] = "wout@example6.com";
        $form['login[password]'] = "12345678";
        $client->submit($form);
        $client->followRedirect();

        // Assert that the user is redirected to the homepage
        $this->assertSame('/', $client->getRequest()->getPathInfo());
    }


}
