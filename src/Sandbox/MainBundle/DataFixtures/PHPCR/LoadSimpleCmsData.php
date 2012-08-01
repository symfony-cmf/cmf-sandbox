<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use PHPCR\Util\NodeHelper;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\Page;

class LoadSimpleCmsData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 50;
    }

    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();
        $basepath = $this->container->getParameter('symfony_cmf_simple_cms.basepath');

        if ($session->nodeExists($basepath)) {
            $session->removeItem($basepath);
        }
        NodeHelper::createPath($session, $basepath);
        $base = $manager->find(null, $basepath);

        $root = $this->createPage($manager, $base, 'service', 'root', 'root page of service menu, never used', '');
        $this->createPage($manager, $root, 'about', 'About us', 'Some information about us', 'The about us page with some content');
        $this->createPage($manager, $root, 'contact', 'Contact', 'A contact page', 'Please send an email to symfony-cmf-devs@groups.google.com');

        $manager->flush();
    }

    /**
     * @return Page instance with the specified information
     */
    protected function createPage($manager, $parent, $name, $label, $title, $body)
    {
        $page = new Page();
        $page->setPosition($parent, $name);
        $page->setLabel($label);
        $page->setTitle($title);
        $page->setBody($body);

        $manager->persist($page);

        return $page;
    }
}
