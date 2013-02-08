<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAware;

class LoadBlogRouteData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 22;
    }

    /**
     * @param \Doctrine\ODM\PHPCR\DocumentManager $dm
     */
    public function load(ObjectManager $dm)
    {
        $blog = $dm->find(null, '/cms/content/CMF Blog');
        $blogRouteManager = $this->container->get('symfony_cmf_blog.blog_route_manager');
        $blogRouteManager->syncRoutes($blog);
        $dm->flush();
    }
}
