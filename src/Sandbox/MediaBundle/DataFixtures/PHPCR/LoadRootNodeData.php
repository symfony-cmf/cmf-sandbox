<?php

namespace Sandbox\MediaBundle\DataFixtures\PHPCR;

use Doctrine\Common\Persistence\ObjectManager;
use PHPCR\Util\NodeHelper;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Yaml;

use Symfony\Component\DependencyInjection\ContainerAware;

class LoadRootNodeData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{

    public function getOrder()
    {
        return 10;
    }

    /**
     * Create default root node
     *
     * @param \Doctrine\ODM\PHPCR\DocumentManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();

        // Create media root
        $mediapath = $this->container->getParameter('sandbox_media.media_basepath');
        $rootMedia = $manager->find(null, $mediapath);

        if (!$rootMedia) {
            $segments = preg_split('#/#', $mediapath, null, PREG_SPLIT_NO_EMPTY);

            if (count($segments) > 1) {
                $nodename = array_pop($segments);
                $rootpath = '/'.join($segments, '/');
            } else {
                $nodename = $segments[0];
                $rootpath = '/';
            }

            NodeHelper::createPath($session, $rootpath);
            $root = $manager->find(null, $rootpath);

            $rootMedia = new \Sandbox\MediaBundle\Document\MediaRoot();
            $rootMedia->setParent($root);
            $rootMedia->setNodename($nodename);

            $manager->persist($rootMedia);
            $manager->flush();
        }

        // Create gallery root
        $gallerypath = $this->container->getParameter('sandbox_media.gallery_basepath');
        $rootGallery = $manager->find(null, $gallerypath);

        if (!$rootGallery) {
            $segments = preg_split('#/#', $gallerypath, null, PREG_SPLIT_NO_EMPTY);

            if (count($segments) > 1) {
                $nodename = array_pop($segments);
                $rootpath = '/'.join($segments, '/');
            } else {
                $nodename = $segments[0];
                $rootpath = '/';
            }

            NodeHelper::createPath($session, $rootpath);
            $root = $manager->find(null, $rootpath);

            $rootGallery = new \Sandbox\MediaBundle\Document\GalleryRoot();
            $rootGallery->setParent($root);
            $rootGallery->setNodename($nodename);

            $manager->persist($rootGallery);
            $manager->flush();
        }
    }
}
