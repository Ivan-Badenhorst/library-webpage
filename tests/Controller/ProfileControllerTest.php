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
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileControllerTest extends WebTestCase
{
//    public function testProfile()
//    {
//        $client = static::createClient();
//
//        // Create a mock file for profile picture
//        $profilePicture = new UploadedFile(
//            __DIR__ . '/../../public/img/defaultProfilePicture.png',
//            'profile_picture.jpg',
//            'image/jpeg',
//            null,
//            true
//        );
//
//        // Make a request to the profile page
//        $crawler = $client->request('GET', '/profile');
//
//        // Assert that the response is successful
//        $this->assertResponseIsSuccessful();
//
//        // Fill and submit the personal info form
//        $form = $crawler->filter('form[name="personal_info"]')->form();
//        $form['personal_info[firstName]'] = "Wout";
//        $form['personal_info[lastName]'] = "Example";
//        $form['personal_info[dateOfBirth]'] = "2008-08-08";
//        $form['personal_info[displayName]'] = "Wout";
//        $form['personal_info[email]'] = "wout@example.com";
//        $form['personal_info[bio]'] = "Example";
//        $form['personal_info[street]'] = "Example";
//        $form['personal_info[postalCode]'] = "1234";
//        $form['personal_info[city]'] = "Example";
//        $form['profilePicture[profilePicture]'] = $profilePicture;
//        $client->submit($form);
//
//        // Assert that the form submission was successful
//        $this->assertResponseIsSuccessful();
//
//        // Assert that the profile information is updated correctly
//        $this->assertSelectorTextContains('div.name h2', 'Wout Example');
//
//        /*// Fill and submit the security and privacy form
//        $form = $crawler->filter('form[name="security_privacy"]')->form();
//        $form['security_privacy[password]'] = 'newpassword';
//        // ... Fill in other form fields as needed
//        $client->submit($form);
//
//        // Assert that the form submission was successful
//        $this->assertResponseIsSuccessful();
//
//        // Assert that the password is updated correctly
//        $this->assertSelectorTextContains('div', 'Password updated successfully');
//        // ... Assert other fields as needed*/
//    }
}
