<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use PHPCR\RepositoryInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use PHPCR\Util\NodeHelper;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Cmf\Bundle\BlogBundle\Document\BlogNode;
use Symfony\Cmf\Bundle\BlogBundle\Document\MultilangBlogNode;

class LoadBlogData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 60;
    }

    /**
     * @param \Doctrine\ODM\PHPCR\DocumentManager $dm
     */
    public function load(ObjectManager $dm)
    {
        $session = $dm->getPhpcrSession();

        $basepath = $this->container->getParameter('symfony_cmf_blog.blog_basepath');
        $content_path = $this->container->getParameter('symfony_cmf_content.content_basepath');

        NodeHelper::createPath($session, $basepath);
        $root = $dm->find(null, $basepath);

        // generate some blog data here..

        $dm->flush();
    }
}

