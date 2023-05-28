<?php

namespace App\Tests\Panther;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Symfony\Component\Panther\PantherTestCase;

class BookInfoTest extends PantherTestCase
{

    public function testOpen(): void{
        //open without login:
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/');

        // Find the first div with ID "book-container"
        $div = $crawler->filter('#book-container')->first();

        // Get the URL of the link inside the div
        $linkUrl = 'http://127.0.0.1:9080/book-info/26';

        // Click on the div
        $div->click();

        // Assert the current URL after the click
        $this->assertSame($linkUrl, $client->getCurrentURL());


        //open with login:

        $crawler = $client->request('GET', '/');
        $logoutButton = $crawler->filter('a.normalButton')->first();
        $crawler = $client->click($logoutButton->link());

        $form = $crawler->filter('form[name="login"]')->form();
        $form['login[email]'] = 'newUser@email.com'; // Update with the desired email
        $form['login[password]'] = 'password'; // Update with the desired password

        $crawler = $client->submit($form);

        // Find the first div with ID "book-container"
        $div = $crawler->filter('#book-container')->first();

        // Get the URL of the link inside the div
        $linkUrl = 'http://127.0.0.1:9080/book-info/26';

        // Click on the div
        $div->click();

        // Assert the current URL after the click
        $this->assertSame($linkUrl, $client->getCurrentURL());

    }

    public function testFavourites(): void{

        //when not logged in button should not be there:

        //open without login:
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/book-info/26');

        // Find the button with ID "book_add_add_to_favorites"
        $buttonExists = $crawler->filter('#book_add_add_to_favorites')->count() > 0;

        // Assert the existence of the button
        $this->assertFalse($buttonExists);

        //when logged in the button should be there:

        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form[name="login"]')->form();
        $form['login[email]'] = 'newUser@email.com'; // Update with the desired email
        $form['login[password]'] = 'password'; // Update with the desired password

        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/book-info/26');

        // Find the button with ID "book_add_add_to_favorites"
        $buttonExists = $crawler->filter('#book_add_add_to_favorites')->count() > 0;

        // Assert the existence of the button
        $this->assertTrue($buttonExists);


        $button = $crawler->filter('#book_add_add_to_favorites')->first();
        // Get the text of the button
        $buttonTextBeforeClick = $button->text();
        echo "before: ".$buttonTextBeforeClick;
        // Click on the button
        $button->click();
        sleep(1);

        // Get the text of the button again
        $buttonTextAfterClick = $button->text();
        echo "after: ".$buttonTextAfterClick;

        self::assertTrue((($buttonTextAfterClick=="Add to favorites" && $buttonTextBeforeClick == "Remove from favorites")||($buttonTextBeforeClick=="Add to favorites" && $buttonTextAfterClick == "Remove from favorites")));
    }

    /**
     * this test checks to see if the right buttons and boxes are visible when we look at bookinfo
     * it also checks if we are able to write a review
     */
    public function testReviews(): void {
        // Load the page containing the book reviews
        $client = static::createPantherClient();
        // Go to book info page
        $crawler = $client->request('GET', '/book-info/26');

        // test that when you are not logged in, the review textbox is disabled
        $textAreaDis = $crawler->filter('#write_review_comment')->isEnabled();
        $this->assertFalse($textAreaDis);

        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form[name="login"]')->form();
        $form['login[email]'] = 'newUser@email.com'; // Update with the desired email
        $form['login[password]'] = 'password'; // Update with the desired password

        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/book-info/26');

        // Find the button with ID "book_review_view_reviews" and check its existence
        $buttonExists = $crawler->filter('#book_review_view_reviews')->count() > 0;
        $this->assertTrue($buttonExists);

        // Click on the button to see reviews
        $button = $crawler->filter('#book_review_view_reviews')->first();
        $button->click();
        sleep(1);
        self::assertTrue($button->text()=="see more...");

        // Find the text with ID "book_review_view_reviews" and check if it exists
        $textAreaExists = $crawler->filter('#write_review_comment')->count() > 0;
        $this->assertTrue($textAreaExists);

        //This checks if you're able to write a review when logged in
        $textAreaDis = $crawler->filter('#write_review_comment')->isEnabled();
        $this->assertTrue($textAreaDis);

        // Submit a review
        $form = $crawler->filter('#write_review_comment')->sendKeys("This is a great book!");

        // Find the button with ID "book_review_view_reviews" and check its existence
        $buttonExists = $crawler->filter('#write_review_submit_review')->count() > 0;
        $this->assertTrue($buttonExists);

        // Click on the button to see reviews
        $button = $crawler->filter('#write_review_submit_review')->first();
        $button->click();
        sleep(1);

        $textAreaEmpty = $crawler->filter('#write_review_comment')->text();
        self::assertTrue($textAreaEmpty=="");
    }








}