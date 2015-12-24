<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class StaticPageTest extends WebTestCase
{
    /**
     * @dataProvider contentDataProvider
     */
    public function testContent($url, $title)
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', $url);

        $this->assertResponseSuccess($client->getResponse());

        $this->assertGreaterThanOrEqual(1, $crawler->filter(sprintf('h1:contains("%s")', $title))->count());
    }

    public function contentDataProvider()
    {
        return array(
            array('/en', 'Homepage'),
            array('/fr', 'Page principale'),
            array('/de', 'Startseite'),
            array('/en/projects', 'The projects'),
            array('/en/projects/cmf', 'Content Management Framework'),
            array('/en/company', 'The Company'),
            array('/en/company/team', 'The Team'),
            array('/fr/company/team', 'The Team'),
            array('/de/company/team', 'The Team'),
            array('/en/company/more', 'More Information'),
            array('/demo', 'Routing demo'),
            array('/demo/controller', 'Explicit Controller'),
            array('/demo/atemplate', 'Explicit template'),
            array('/demo/type', 'Controller by type'),
            array('/demo/class', 'Controller by class'),
            array('/hello', 'Hello World!'),
            array('/about', 'Some information about us'),
        );
    }

    public function testJson()
    {
        $client = $this->createClient();
        $client->request('GET', '/en/company/team', array(), array(), array(
                'HTTP_ACCEPT' => 'application/json',
            )
        );
        $response = $client->getResponse();
        $this->assertResponseSuccess($response);
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
        $this->assertContains('"title":"The Team",', $response->getContent());
    }
}
