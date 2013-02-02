<?php

namespace Sandbox;

class BlogAdminTest extends WebTestCase
{
    public function testList()
    {
        $client = $this->createClient();

        $client->request('GET', '/en/admin/bundle/blog/blog/list');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Blogs', $response->getContent());

        $menuCount = $this->isSearchSupported() ? 15 : 14;
        $this->assertContains("$menuCount results", $response->getContent());
        $this->assertContains('Explicit template', $response->getContent());
    }
}

