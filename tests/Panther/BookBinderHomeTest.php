<?php

namespace App\Tests\Panther;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Symfony\Component\Panther\PantherTestCase;

class BookBinderHomeTest extends PantherTestCase
{

    public function testOpen(): void{

        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/');
        //testing navigator:
        $ulElement = $crawler->filter('header ul')->first();
        $this->assertNotEmpty($ulElement, 'The <ul> element in the header was not found.');

        $listItems = $ulElement->filter('li');
        $expectedListItemsCount = 6;
        $this->assertCount($expectedListItemsCount, $listItems, 'The number of list items in navigator does not match.');

        $listItemsCount = count($listItems);
        $listExpected = ["Chats", "Reading list", "About", "Meetup", "Contact", "Profile"];
        for ($i = 0; $i < $listItemsCount; $i++) {
            $listItem = $listItems->eq($i);

            $this->assertStringContainsString($listExpected[$i], $listItem->text(), 'Text: "'.$listExpected[$i].'" not found in navigation.');
            // Add more assertions as needed
        }


        $logoutButton = $crawler->filter('a.normalButton')->first();
        $this->assertNotEmpty($logoutButton, 'The <a> element was not found.');

        $linkText = $logoutButton->text();
        $expectedText = 'Login'; // Update this value with the expected link text

        $this->assertSame($expectedText, $linkText, 'The login button is not rendered correctly');
       // $client->close();
    }

    public function testOpenWithLogin(): void{
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/');

        $logoutButton = $crawler->filter('a.normalButton')->first();
        $crawler = $client->click($logoutButton->link());//$logoutButton->click();

        // Get the current URL after redirection
        $currentUrl = $client->getCurrentURL();

        // Assert the expected redirection URL
        $expectedRedirectUrl = 'http://127.0.0.1:9080/login'; // Update with the expected URL
        $this->assertSame($expectedRedirectUrl, $currentUrl, 'Login button did not redirect to correct page');

        //next I login:
        $form = $crawler->filter('form[name="login"]')->form();

        $formExists = $crawler->filter('form[name="login"]')->count() > 0;
        $this->assertTrue($formExists, 'The login form does not exist on the page.');

        $form['login[email]'] = 'newUser@email.com'; // Update with the desired email
        $form['login[password]'] = 'password'; // Update with the desired password

        // Submit the form
        $crawler = $client->submit($form);
        $currentUrl = $client->getCurrentURL();

        //did we redirect to the home page
        $expectedRedirectUrl = 'http://127.0.0.1:9080/';
        $this->assertSame($expectedRedirectUrl, $currentUrl, 'URL after login clicked');

        //check if login button is now logout
        $logoutButton = $crawler->filter('a.normalButton')->first();
        $this->assertNotEmpty($logoutButton, 'The <a> element was not found.');

        $linkText = $logoutButton->text();
        $expectedText = 'Logout'; // Update this value with the expected link text

        $this->assertSame($expectedText, $linkText, 'The logout button is not rendered correctly');

        //$client->close();
    }

    public function testBookSearch(): void{

        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/');

        //search with 0 results
        $searchInput = $crawler->filterXPath('//input[@id="book_search_search_term"]')->first();
        $searchInput->sendKeys('abcdqporn ad');

        // Find the submit button by its ID and click it
        $submitButton = $crawler->filterXPath('//button[@id="book_search_search"]')->first();
        $crawler = $client->submit($submitButton->form());

        //next I will check the search results: -> for hunger I know there are 2 results
        // Find the container div with the class "book-listings"
        $containerDiv = $crawler->filter('div.book-listings')->first();


        $this->waitForSearch($crawler, $client, 1);
        $crawler = $client->refreshCrawler();
        $divCount = $containerDiv->filter('div')->count();

        // Assert the expected number of div elements
        $expectedCount = 0; // Update with the expected count
        $this->assertEquals($expectedCount*2, $divCount, 'The actual count of book elements does not match the expected count.');


        //search with 2 results
        $client->executeScript('arguments[0].value = "";', [$searchInput->getElement(0)]);

        $searchInput->sendKeys('hunger');
        $crawler = $client->submit($submitButton->form());
        $this->waitForSearch($crawler, $client, 2);
        $crawler = $client->refreshCrawler();
        $divCount = $containerDiv->filter('div')->count();
        $expectedCount = 2;// Update with the expected count
        $this->assertEquals($expectedCount*2, $divCount, 'The actual count of book elements does not match the expected count.');


        //search with 4 results
        $client->executeScript('arguments[0].value = "";', [$searchInput->getElement(0)]);

        $searchInput->sendKeys('por');
        $crawler = $client->submit($submitButton->form());
        $this->waitForSearch($crawler, $client, 4);
        $crawler = $client->refreshCrawler();
        $divCount = $containerDiv->filter('div')->count();
        $expectedCount = 4; // Update with the expected count
        $this->assertEquals($expectedCount*2, $divCount, 'The actual count of book elements does not match the expected count.');


        //search with more results than can fit on a page
        $client->executeScript('arguments[0].value = "";', [$searchInput->getElement(0)]);

        $searchInput->sendKeys('a');
        $crawler = $client->submit($submitButton->form());
        $this->waitForSearch($crawler, $client, 40);
        $crawler = $client->refreshCrawler();
        $divCount = $containerDiv->filter('div')->count();
        $expectedCount = 40; // Update with the expected count
        $this->assertEquals($expectedCount*2, $divCount, 'The actual count of book elements does not match the expected count.');



    }




    public function testFilterValues(): void{
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/');

        $checkboxes = $crawler->filter('.checkboxes input[type="checkbox"]');
        $labels = $crawler->filterXPath('//div[@class="checkboxes"]//label');

//
        $labelTexts = [];
        $labels->each(function ($label) use (&$labelTexts) {
            $labelTexts[] = $label->text();
        });

        // Assert the labels of the checkboxes
        $expectedLabels = ['Fantasy', 'ScienceFiction', 'Mystery', 'Romance', 'Thriller', 'Horror', 'Academic', 'Biography'];//known from database
        $this->assertEquals($expectedLabels, $labelTexts, 'Checkbox labels do not match.');

    }


    public function testFiltering(): void{

        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/');

        //to test filtering:
        /*
         * Search for term with few results
         * Apply filters leading to known results
         * Count results
         */


        //search with 2 results
        $searchInput = $crawler->filterXPath('//input[@id="book_search_search_term"]')->first();
        $searchInput->sendKeys('hunger');
        $submitButton = $crawler->filterXPath('//button[@id="book_search_search"]')->first();
        $crawler = $client->submit($submitButton->form());
        $containerDiv = $crawler->filter('div.book-listings')->first();
        $this->waitForSearch($crawler, $client, 1);
        $crawler = $client->refreshCrawler();
        $divCount = $containerDiv->filter('div')->count();


        $checkboxes = $crawler->filter('input[type="checkbox"]')->slice(0, 2);
        //Click the first two checkboxes
        $checkboxes->each(function ($checkbox) use ($client) {
            $client->executeScript('arguments[0].checked = false;', [$checkbox]);
           // $client->executeScript('arguments[0].dispatchEvent(new Event("change"))', [$checkbox]);
        });
        $client->executeScript('
            const formElement = document.getElementById("filter_genre");
            const event = new Event("change");
            formElement.dispatchEvent(event);
        ');

        sleep(8);

        $screenshotPath = 'filter.png';
        $client->takeScreenshot($screenshotPath);

        $divCount = $containerDiv->filter('div')->count();
        $expectedCount = 1; // Update with the expected count
        $this->assertEquals($expectedCount*2, $divCount, 'The actual count of book elements does not match the expected count.');

    }




    /**
     * @param Crawler|\Symfony\Component\DomCrawler\Crawler $crawler
     * @param Client $client
     * @param $expectedResults
     */
    public function waitForSearch(Crawler|\Symfony\Component\DomCrawler\Crawler $crawler, Client $client, $expectedResults): void
    {
        $timeout = 10; // Maximum time to wait in seconds
        $start = time();
        while (time() - $start < $timeout) {
            $innerDivs = $crawler->filter('.book-listings .book-container');

            if ($innerDivs->count() < 40 && $innerDivs->count() >= $expectedResults) {
                // Desired condition met, exit the loop
                break;
            }

            sleep(1); // Wait for 1 second before rechecking the condition
            $crawler = $client->refreshCrawler(); // Refresh the crawler to get updated content
        }
    }




}
