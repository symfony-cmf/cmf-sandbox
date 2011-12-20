<?php

namespace Symfony\Cmf\Bundle\MenuBundle\Resources\data\fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Bundle\PHPCRBundle\JackalopeLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Symfony\Cmf\Bundle\MenuBundle\Document\MenuItem;

class LoadMenuData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{

    protected $dm;


    protected $session;

    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->session = $this->container->get('doctrine_phpcr.default_session'); // FIXME: should get this from manager in load, not necessarily the default
    }

    public function getOrder() {
        return 10;
    }

    public function load($manager)
    {
        $this->dm = $manager;

        $base_path = $this->container->getParameter('symfony_cmf_menu.menu_basepath');
        $content_path = $this->container->getParameter('symfony_cmf_content.static_basepath');

        $this->createPath($base_path);

        $this->createMenuItem("$base_path/main", 'Main menu', 'Home', $this->dm->find(null, "$content_path/home"));
        $this->createMenuItem("$base_path/main/first-item", 'Firstitem', 'First (Projects)', $this->dm->find(null, "$content_path/projects"));
        $this->createMenuItem("$base_path/main/first-item/test-item", 'Testitem', 'Hello World!', null, null, 'test');
        $this->createMenuItem("$base_path/main/second-item", 'Seconditem', 'Second (Company)', $this->dm->find(null, "$content_path/company"));
        $this->createMenuItem("$base_path/main/second-item/child-item", 'Seconditemchild', 'Second Child (Company)', $this->dm->find(null, "$content_path/company/more"));
        $this->createMenuItem("$base_path/main/second-item/external", 'External Link', 'External Link', null, 'http://cmf.symfony.com/');

        $this->dm->flush();
    }

    /**
     * @return a Navigation instance with the specified information
     */
    protected function createMenuItem($path, $name, $label, $content, $uri = null, $route = null)
    {
        // Remove the node if it already exists
        if ($old_node = $this->dm->find(null, $path)) {
            $this->dm->remove($old_node);
            // FIXME: we need to flush here to avoid error about "node existing".
            // this is a bug in phpcr-odm http://www.doctrine-project.org/jira/browse/PHPCR-34
            $this->dm->flush();
        }

        $menuitem = new MenuItem();
        $menuitem->setPath($path);
        $menuitem->setName($name);
        $menuitem->setLabel($label);
        if (null !== $content) {
            $menuitem->setContent($content);
        } elseif (null !== $uri) {
            $menuitem->setUri($uri);
        } else if (null !== $route) {
            $menuitem->setRoute($route);
        }

        $this->dm->persist($menuitem);
    }

    /**
     * Create a node and it's parents, if necessary.  Like mkdir -p.
     *
     * TODO: clean this up once the id generator stuff is done as intended
     *
     * @param string $path  full path, like /cms/navigation/main
     * @return Node the (now for sure existing) node at path
     */
    public function createPath($path)
    {
        $current = $this->session->getRootNode();

        $segments = preg_split('#/#', $path, null, PREG_SPLIT_NO_EMPTY);
        foreach ($segments as $segment) {
            if ($current->hasNode($segment)) {
                $current = $current->getNode($segment);
            } else {
                $current = $current->addNode($segment);
            }
        }

        return $current;
    }
}
