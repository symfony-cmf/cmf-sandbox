<?php

namespace Sandbox;

class BlogControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/en/blog');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertGreaterThan(1, $crawler->filter('div.post')->count());
    }
}

