<?php

namespace Sandbox;

class AdminDashboardTest extends WebTestCase
{
    public function testRedirectToDashboard()
    {
        $client = $this->createClient();

        $client->request('GET', '/admin');

        $this->assertEquals(301, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertEquals('http://localhost/en/admin/dashboard', $client->getRequest()->getUri());

        $client->request('GET', '/admin/dashboard');

        $this->assertEquals(301, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertEquals('http://localhost/en/admin/dashboard', $client->getRequest()->getUri());
    }

    public function testContents()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/en/admin/dashboard');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Sonata Admin', $response->getContent());

        $this->assertCount(2, $crawler->filter('.container-fluid'));
        $this->assertCount(15, $crawler->filter('.sonata-ba-list-label'));
    }
}
