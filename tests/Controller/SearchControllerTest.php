<?php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    public function testSearch()
    {
        $client = static::createClient();

        // Simulate a GET request to the /search/{title}/{genres}/{offset} route
        $client->request('GET', '/search/{title}/{genres}/{offset}');

        // Replace {title}, {genres}, and {offset} with actual values for testing
        $title = 'example_title';
        $genres = 'genre1,genre2,genre3';
        $offset = 0;

        $route = sprintf('/search/%s/%s/%d', $title, $genres, $offset);
        $client->request('GET', $route);

        $response = $client->getResponse();

        // Assert that the response is successful
        $this->assertResponseIsSuccessful();

        // Assert that the response content is valid JSON
        $this->assertJson($response->getContent());

        // Decode the JSON response into an array for further assertions
        $responseData = json_decode($response->getContent(), true);

        // Assert that the response data contains the expected keys or values
        //$this->assertArrayHasKey('book', $responseData);
        //$this->assertArrayHasKey('author', $responseData);
        // Add more assertions as needed to check the structure and content of the response data
    }
}
