<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class HomepageTest extends WebTestCase
{
    public function testRedirectToDefaultLanguage()
    {
        $client = $this->createClient();

        $client->request('GET', '/');

        $this->assertEquals(301, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertContains('http://localhost/en', $client->getRequest()->getUri());
    }

    public function testContents()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/en');

        $this->assertResponseSuccess($client->getResponse());

        $this->assertCount(3, $crawler->filter('.cmf-block'));
        $this->assertCount(1, $crawler->filter('h1:contains(Homepage)'));
        $this->assertCount(1, $crawler->filter('h2:contains("Welcome to the Symfony CMF Demo")'));

        $menuCount = $this->isSearchSupported() ? 22 : 21;
        $this->assertCount($menuCount, $crawler->filter('.panel-nav li'));
    }

    public function testJsonContents()
    {
        $client = $this->createClient();

        $client->request(
            'GET',
            '/en',
            array(),
            array(),
            array(
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            )
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = @json_decode($client->getResponse()->getContent());
        $this->assertNotEmpty($json);
    }
}
