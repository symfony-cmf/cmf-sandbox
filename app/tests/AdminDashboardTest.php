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

        $this->assertEquals('http://localhost/admin/', $client->getRequest()->getUri());

        $this->assertEquals(301, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertEquals('http://localhost/admin/dashboard', $client->getRequest()->getUri());
    }

    public function testContents()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/dashboard');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Sonata Admin', $response->getContent());

        $this->assertCount(2, $crawler->filter('.container-fluid'));
        $this->assertCount(7, $crawler->filter('.sonata-ba-list-label'));
    }
}
