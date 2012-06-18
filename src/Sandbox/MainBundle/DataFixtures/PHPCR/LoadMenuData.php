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

    public function load(ObjectManager $manager)
    {
        $this->dm = $manager;

        $base_path = $this->container->getParameter('symfony_cmf_menu.menu_basepath');
        $content_path = $this->container->getParameter('symfony_cmf_content.static_basepath');

        $this->createPath($base_path);
        $root = $this->dm->find(null, $base_path);


        /** @var $menuitem MenuItem */
        $main = $this->createMenuItem($root, 'main', array('en' => 'Home', 'de' => 'Start', 'fr' => 'Accueil'), $this->dm->find(null, "$content_path/home"));
        $main->setChildrenAttributes(array("class" => "menu_main"));

        $this->createMenuItem($main, 'admin-item', 'Admin', null, null, 'sonata_admin_dashboard');

        $projects = $this->createMenuItem($main, 'projects-item', array('en' => 'Projects', 'de' => 'Projekte', 'fr' => 'Projets'), $this->dm->find(null, "$content_path/projects"));
        $this->createMenuItem($projects, 'cmf-item', 'Symfony CMF', $this->dm->find(null, "$content_path/cmf"));

        $company = $this->createMenuItem($main, 'company-item', array('en' => 'Company', 'de' => 'Firma', 'fr' => 'Entreprise'), $this->dm->find(null, "$content_path/company"));
        $this->createMenuItem($company, 'team-item', array('en' => 'Team', 'de' => 'Team', 'fr' => 'Equipe'), $this->dm->find(null, "$content_path/team"));
        $this->createMenuItem($company, 'more-item', array('en' => 'More', 'de' => 'Mehr', 'fr' => 'Plus'), $this->dm->find(null, "$content_path/more"));

        $demo = $this->createMenuItem($main, 'demo-item', 'Demo', $this->dm->find(null, "$content_path/demo"));
        //TODO: this should be possible without a content as the controller might not need a content. support directly having the route document as "content" in the menu document?
        $this->createMenuItem($demo, 'controller-item', 'Explicit controller', $this->dm->find(null, "$content_path/demo_controller"));
        $this->createMenuItem($demo, 'template-item', 'Explicit template', $this->dm->find(null, "$content_path/demo_template"));
        $this->createMenuItem($demo, 'alias-item', 'Route alias to controller', $this->dm->find(null, "$content_path/demo_alias"));
        $this->createMenuItem($demo, 'class-item', 'Class to controller', $this->dm->find(null, "$content_path/demo_class"));
        $this->createMenuItem($demo, 'test-item', 'Normal Symfony Route', null, null, 'test');
        $this->createMenuItem($demo, 'external-item', 'External Link', null, 'http://cmf.symfony.com/');

        $this->dm->flush();
    }

    /**
     * @return a Navigation instance with the specified information
     */
    protected function createMenuItem($parent, $name, $label, $content, $uri = null, $route = null)
    {
        $menuitem = is_array($label) ? new MultilangMenuItem() : new MenuItem();
        $menuitem->setParent($parent);
        $menuitem->setName($name);

        $this->dm->persist($menuitem); // do persist before binding translation

        if (null !== $content) {
            $menuitem->setContent($content);
        } elseif (null !== $uri) {
            $menuitem->setUri($uri);
        } else if (null !== $route) {
            $menuitem->setRoute($route);
        }

        if (is_array($label)) {
            foreach($label as $locale => $l) {
                $menuitem->setLabel($l);
                $this->dm->bindTranslation($menuitem, $locale);
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
