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

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class WebTestCase extends BaseWebTestCase
{
    protected static $fixturesLoaded = false;

    public function setUp()
    {
        if (self::$fixturesLoaded) {
            return;
        }

        $this->loadFixtures([
            'AppBundle\DataFixtures\PHPCR\LoadStaticPageData',
            'AppBundle\DataFixtures\PHPCR\LoadMenuData',
            'AppBundle\DataFixtures\PHPCR\LoadRoutingData',
        ], null, 'doctrine_phpcr');

        self::$fixturesLoaded = true;
    }

    protected function createClientAuthenticated(array $options = [], array $server = [])
    {
        $server = array_merge($server, [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);

        return $this->createClient($options, $server);
    }

    /**
     * Method to assert a 200 response code.
     *
     * This code is taken from symfony-cmf/Testing.
     *
     * @param Response $response
     * @param string   $url
     */
    protected function assertResponseSuccess(Response $response, $url = '')
    {
        libxml_use_internal_errors(true);

        $dom = new \DomDocument();
        $dom->loadHTML($response->getContent());

        $xpath = new \DOMXpath($dom);
        $result = $xpath->query('//div[contains(@class,"text-exception")]/h1');
        $exception = null;
        if ($result->length) {
            $exception = $result->item(0)->nodeValue;
        }

        $this->assertEquals(
            200,
            $response->getStatusCode(),
            $exception ? 'Exception: "'.trim($exception).'" on url: '.$url : null
        );
    }
}
