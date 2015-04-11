<?php

/*
 * This file is part of the CMF Sandbox package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use PHPCR\RepositoryInterface;
use Doctrine\Common\DataFixtures\Executor\PHPCRExecutor;
use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;

abstract class WebTestCase extends BaseWebTestCase
{
    static protected $fixturesLoaded = false;

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
            'PHP_AUTH_PW'   => 'admin',
        ));
        return $this->createClient($options, $server);
    }
}
