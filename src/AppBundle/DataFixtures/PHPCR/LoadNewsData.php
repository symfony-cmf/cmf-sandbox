<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\PHPCR;

use AppBundle\Document\DemoNewsContent;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadNewsData implements ContainerAwareInterface, FixtureInterface, OrderedFixtureInterface
{
    use ContainerAwareTrait;

    public function getOrder()
    {
        return 50;
    }

    public function load(ObjectManager $manager)
    {
        if (!$manager instanceof DocumentManager) {
            $class = get_class($manager);

            throw new \RuntimeException("Fixture requires a PHPCR ODM DocumentManager instance, instance of '$class' given.");
        }

        $basePath = $this->container->getParameter('cmf_content.persistence.phpcr.content_basepath');
        $newsPath = $basePath.'/news';
        NodeHelper::createPath($manager->getPhpcrSession(), $newsPath);
        $newsRoot = $manager->find(null, $newsPath);

        $news = new DemoNewsContent();
        $news->setParentDocument($newsRoot);
        $news->setTitle('RoutingAutoBundle generates routes!');
        $news->setBody(<<<'EOT'
'This is a news item which demonstrates the routing auto bundle. The routing
auto bundle automatically creates routes for documents which are persisted.

See the routing auto <a href="https://github.com/symfony-cmf/cmf-sandbox/blob/master/src/AppBundle/Resources/config/cmf_routing_auto.yml">configuration file</a> to see how this works.
EOT
    );

        $manager->persist($news);
        $manager->flush();
    }
}
