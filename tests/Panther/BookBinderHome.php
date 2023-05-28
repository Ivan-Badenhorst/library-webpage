<?php

namespace App\Tests\Panther;

use Symfony\Component\Panther\PantherTestCase;

class BookBinderHome extends PantherTestCase
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

    }


}
