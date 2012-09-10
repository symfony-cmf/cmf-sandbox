<?php

namespace Sandbox;

class HomepageTest extends WebTestCase
{
    public function testRedirectToDefaultLanguage()
    {
        $client = $this->createClient();

        $client->request('GET', '/');

        $this->assertEquals(301, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertEquals('http://localhost/en', $client->getRequest()->getUri());
    }

    public function testContents()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/en');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertCount(3, $crawler->filter('.symfony_cmf-block'));
        $this->assertCount(1, $crawler->filter('h1:contains(Homepage)'));
        $this->assertCount(1, $crawler->filter('h2:contains("Welcome to the Symfony CMF Demo")'));

        $menuCount = $this->isSearchSupported() ? 14 : 13;
        $this->assertCount($menuCount, $crawler->filter('ul.menu_main li'));
    }
}
