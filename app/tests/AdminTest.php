<?php

namespace Sandbox;

class AdminTest extends WebTestCase
{
    public function testList()
    {
        $client = $this->createClient();

        $client->request('GET', '/en/admin/bundle/menu/menuitem/list');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Menu Item List', $response->getContent());

        $menuCount = $this->isSearchSupported() ? 10 : 9;
        $this->assertContains("$menuCount results", $response->getContent());
        $this->assertContains('Explicit template', $response->getContent());
    }

    public function testCreate()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/en/admin/bundle/menu/menuitem/create');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Create', $response->getContent());

        $this->assertCount(6, $crawler->filter('.control-group'));
        $this->assertContains('Parent', $crawler->filter('.control-group')->first()->children()->first()->text());
        $this->assertContains(' *', $crawler->filter('.control-group')->first()->children()->first()->text());
    }

    public function testEdit()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/en/admin/bundle/menu/menuitem/cms/menu/main/demo-item/external-item/edit');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Edit &quot;External Link&quot;', $response->getContent());

        $this->assertCount(6, $crawler->filter('.control-group'));
        $this->assertContains('Parent', $crawler->filter('.control-group')->first()->children()->first()->text());
        $this->assertContains(' *', $crawler->filter('.control-group')->first()->children()->first()->text());
    }
}
