<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Functional;

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
        return [
            ['/en', 'Homepage'],
            ['/fr', 'Page principale'],
            ['/de', 'Startseite'],
            ['/en/projects', 'The projects'],
            ['/en/projects/cmf', 'Content Management Framework'],
            ['/en/company', 'The Company'],
            ['/en/company/team', 'The Team'],
            ['/fr/company/team', 'The Team'],
            ['/de/company/team', 'The Team'],
            ['/en/company/more', 'More Information'],
            ['/demo', 'Routing demo'],
            ['/demo/controller', 'Explicit Controller'],
            ['/demo/atemplate', 'Explicit template'],
            ['/demo/type', 'Controller by type'],
            ['/demo/class', 'Controller by class'],
            ['/hello', 'Hello World!'],
            ['/en/about', 'Some information about us'],
        ];
    }

    public function testJson()
    {
        $client = $this->createClient();
        $client->request('GET', '/en/company/team', [], [], [
                'HTTP_ACCEPT' => 'application/json',
            ]
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
