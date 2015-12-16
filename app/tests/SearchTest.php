<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class SearchTest extends WebTestCase
{
    public function testSearch()
    {
        if (!$this->isSearchSupported()) {
            $this->markTestSkipped('Fulltext search is not supported.');
        }

        $client = $this->createClient();

        $client->request('GET', '/search?query=cmf');
        $response = $client->getResponse();

        $this->assertResponseSuccess($response);

        $this->assertContains('results for &quot;cmf&quot; have been found', $response->getContent());
    }
}
