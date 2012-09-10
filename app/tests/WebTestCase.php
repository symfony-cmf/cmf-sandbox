<?php

namespace Sandbox;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
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
            'Sandbox\MainBundle\DataFixtures\PHPCR\LoadStaticPageData',
            'Sandbox\MainBundle\DataFixtures\PHPCR\LoadMenuData',
            'Sandbox\MainBundle\DataFixtures\PHPCR\LoadRoutingData',
            'Sandbox\MainBundle\DataFixtures\PHPCR\LoadSimpleCmsData',
        ), null, 'doctrine_phpcr');

        self::$fixturesLoaded = true;
    }

    protected function isSearchSupported()
    {
        return $this->getContainer()->get('doctrine_phpcr')->getConnection()->nodeExists('/cms/menu/main/search-item');
    }
}
