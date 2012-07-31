<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Symfony\Cmf\Bundle\MenuBundle\Document\MenuItem;
use Symfony\Cmf\Bundle\MultilangContentBundle\Document\MultilangMenuItem;

class LoadMenuData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 10;
    }

    public function load(ObjectManager $dm)
    {
        $session = $dm->getPhpcrSession();

        $base_path = $this->container->getParameter('symfony_cmf_menu.menu_basepath');
        $content_path = $this->container->getParameter('symfony_cmf_content.static_basepath');

        $this->createPath($session, $base_path);
        $root = $dm->find(null, $base_path);

        /** @var $menuitem MenuItem */
        $main = $this->createMenuItem($dm, $root, 'main', array('en' => 'Home', 'de' => 'Start', 'fr' => 'Accueil'), $dm->find(null, "$content_path/home"));
        $main->setChildrenAttributes(array("class" => "menu_main"));

        try {
            // run a dummy query to check if full text search is supported
            // TODO: is this covered by the capabilities API?
            $qb = $dm->createQueryBuilder();
            $factory = $qb->getQOMFactory();
            $qb->from($factory->selector('nt:unstructured'))
                ->where($factory->fullTextSearch('foo', 'bar'))
                ->execute();

            $this->createMenuItem($dm, $main, 'search-item', 'Search', null, null, 'search');
        } catch (\Exception $e) {
            // search not supported
        }

        $this->createMenuItem($dm, $main, 'admin-item', 'Admin', null, null, 'sonata_admin_dashboard');

        $projects = $this->createMenuItem($dm, $main, 'projects-item', array('en' => 'Projects', 'de' => 'Projekte', 'fr' => 'Projets'), $dm->find(null, "$content_path/projects"));
        $this->createMenuItem($dm, $projects, 'cmf-item', 'Symfony CMF', $dm->find(null, "$content_path/cmf"));

        $company = $this->createMenuItem($dm, $main, 'company-item', array('en' => 'Company', 'de' => 'Firma', 'fr' => 'Entreprise'), $dm->find(null, "$content_path/company"));
        $this->createMenuItem($dm, $company, 'team-item', array('en' => 'Team', 'de' => 'Team', 'fr' => 'Equipe'), $dm->find(null, "$content_path/team"));
        $this->createMenuItem($dm, $company, 'more-item', array('en' => 'More', 'de' => 'Mehr', 'fr' => 'Plus'), $dm->find(null, "$content_path/more"));

        $demo = $this->createMenuItem($dm, $main, 'demo-item', 'Demo', $dm->find(null, "$content_path/demo"));
        //TODO: this should be possible without a content as the controller might not need a content. support directly having the route document as "content" in the menu document?
        $this->createMenuItem($dm, $demo, 'controller-item', 'Explicit controller', $dm->find(null, "$content_path/demo_controller"));
        $this->createMenuItem($dm, $demo, 'template-item', 'Explicit template', $dm->find(null, "$content_path/demo_template"));
        $this->createMenuItem($dm, $demo, 'alias-item', 'Route alias to controller', $dm->find(null, "$content_path/demo_alias"));
        $this->createMenuItem($dm, $demo, 'class-item', 'Class to controller', $dm->find(null, "$content_path/demo_class"));
        $this->createMenuItem($dm, $demo, 'test-item', 'Normal Symfony Route', null, null, 'test');
        $this->createMenuItem($dm, $demo, 'external-item', 'External Link', null, 'http://cmf.symfony.com/');

        $dm->flush();
    }

    /**
     * @return a Navigation instance with the specified information
     */
    protected function createMenuItem($dm, $parent, $name, $label, $content, $uri = null, $route = null)
    {
        $menuitem = is_array($label) ? new MultilangMenuItem() : new MenuItem();
        $menuitem->setParent($parent);
        $menuitem->setName($name);

        $dm->persist($menuitem); // do persist before binding translation

        if (null !== $content) {
            $menuitem->setContent($content);
        } elseif (null !== $uri) {
            $menuitem->setUri($uri);
        } else if (null !== $route) {
            $menuitem->setRoute($route);
        }

        if (is_array($label)) {
            foreach ($label as $locale => $l) {
                $menuitem->setLabel($l);
                $dm->bindTranslation($menuitem, $locale);
            }
        } else {
            $menuitem->setLabel($label);
        }

        return $menuitem;
    }

    /**
     * Create a node and it's parents, if necessary.  Like mkdir -p.
     *
     * TODO: clean this up once the id generator stuff is done as intended
     *
     * @param string $path  full path, like /cms/navigation/main
     * @return Node the (now for sure existing) node at path
     */
    public function createPath($session, $path)
    {
        $current = $session->getRootNode();

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
