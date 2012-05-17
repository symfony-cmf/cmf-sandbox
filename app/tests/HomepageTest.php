<?php

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Executor\PHPCRExecutor;
use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;

class HomepageTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        static::purgeAndloadFixtures(array(
            'Sandbox\MainBundle\DataFixtures\PHPCR\LoadStaticPageData',
            'Sandbox\MainBundle\DataFixtures\PHPCR\LoadRoutingData',
            'Sandbox\MainBundle\DataFixtures\PHPCR\LoadMenuData',
        ));
    }

    public function testRedirectToDefaultLanguage()
    {
        $client = $this->createClient();

        $client->request('GET', '/');

        $this->assertEquals(301, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertEquals('http://localhost/en', $client->getRequest()->getUri());
    }

    protected static function purgeAndloadFixtures(array $fixtures)
    {
        static::createClient();

        $fixturesToLoad = array();

        foreach ($fixtures as $fixture) {
            if (!class_exists($fixture)) {
                throw new \Exception(sprintf('Class %s not exists.', $fixture));
            }

            $fixtureToLoad = new $fixture;
            $fixtureToLoad->setContainer(static::$kernel->getContainer());

            $fixturesToLoad[] = $fixtureToLoad;
        }

        $registry = static::$kernel->getContainer()->get('doctrine_phpcr');
        $dm = $registry->getManager(null);

        $purger = new PHPCRPurger($dm);

        $executor = new PHPCRExecutor($dm, $purger);
        $executor->execute($fixturesToLoad);
    }
}