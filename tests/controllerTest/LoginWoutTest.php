<?php

namespace App\Tests\controllerTest;

use Symfony\Component\Panther\PantherTestCase;

class LoginWoutTest extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
