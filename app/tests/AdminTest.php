<?php

namespace Sandbox;

class AdminTest extends WebTestCase
{
    public function testList()
    {
        $client = $this->createClientAuthenticated();

        $client->request('GET', '/en/admin/cmf/menu/menunode/list');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Menus', $response->getContent());

        $menuCount = $this->isSearchSupported() ? 17 : 16;
        $this->assertContains("$menuCount results", $response->getContent());
        $this->assertContains('Explicit template', $response->getContent());
    }

    public function testCreate()
    {
        $client = $this->createClientAuthenticated();

        $crawler = $client->request('GET', '/en/admin/cmf/menu/menunode/create');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Create', $response->getContent());

        $this->assertCount(11, $crawler->filter('.control-group'));
        $this->assertContains('Parent', $crawler->filter('.control-group')->first()->children()->first()->text());
        $this->assertContains(' *', $crawler->filter('.control-group')->first()->children()->first()->text());
    }

    public function testEdit()
    {
        $client = $this->createClientAuthenticated();

        $crawler = $client->request('GET', '/en/admin/cmf/menu/menunode/cms/menu/main/demo-item/external-item/edit');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Edit &quot;External Link&quot;', $response->getContent());

        $this->assertCount(11, $crawler->filter('.control-group'));
        $this->assertContains('Parent', $crawler->filter('.control-group')->first()->children()->first()->text());
        $this->assertContains(' *', $crawler->filter('.control-group')->first()->children()->first()->text());
    }
}
