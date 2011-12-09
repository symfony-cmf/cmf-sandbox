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

        $this->createMenuItem("/menus", 'Main menu', '', '');
        $this->createMenuItem("/menus/main", 'Main menu', 'Home', '/app_dev.php');
        $this->createMenuItem("/menus/main/home-item", 'Home', 'Home', '/app_dev.php');
        $this->createMenuItem("/menus/main/company-item", 'Company', 'Company', '/app_dev.php/company');
        $this->createMenuItem("/menus/main/company-item/team-item", 'Team', 'Team', '/app_dev.php/company/team');
        $this->createMenuItem("/menus/main/company-item/more-item", 'More', 'More', '/app_dev.php/company/more');
        $this->createMenuItem("/menus/main/projects-item", 'Projects', 'Projects', '/app_dev.php/projects');
        $this->createMenuItem("/menus/main/projects-item/cmf-item", 'CMF', 'CMF', '/app_dev.php/projects/cmf');

        $this->dm->flush();
    }

    /**
     * @return a Navigation instance with the specified information
     */
    protected function createMenuItem($path, $name, $label, $uri, $route = null)
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
        $menuitem->setName($label);
        $menuitem->setLabel($label);
        if ($uri !== null) {
            $menuitem->setUri($uri);
        } else if ($route !== null) {
            $menuitem->setRoute($route);
        }

        $this->dm->persist($menuitem);
    }

}
