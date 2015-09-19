<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use PHPCR\RepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class WebTestCase extends BaseWebTestCase
{
    protected static $fixturesLoaded = false;

    public function setUp()
    {
        if (self::$fixturesLoaded) {
            return;
        }

        $this->loadFixtures(array(
            'AppBundle\DataFixtures\PHPCR\LoadStaticPageData',
            'AppBundle\DataFixtures\PHPCR\LoadMenuData',
            'AppBundle\DataFixtures\PHPCR\LoadRoutingData',
            'AppBundle\DataFixtures\PHPCR\LoadSimpleCmsData',
        ), null, 'doctrine_phpcr');

        self::$fixturesLoaded = true;
    }

    protected function isSearchSupported()
    {
        return $this->getContainer()
            ->get('doctrine_phpcr')
            ->getConnection()
            ->getRepository()
            ->getDescriptor(RepositoryInterface::QUERY_FULL_TEXT_SEARCH_SUPPORTED)
        ;
    }

    protected function createClientAuthenticated(array $options = array(), array $server = array())
    {
        $server = array_merge($server, array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ));

        return $this->createClient($options, $server);
    }

    /**
     * Method to assert a 200 response code.
     *
     * This code is taken from symfony-cmf/Testing.
     */
    protected function assertResponseSuccess(Response $response)
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

        $this->assertEquals(200, $response->getStatusCode(), $exception ? 'Exception: "'.trim($exception).'"' : null);
    }
}
