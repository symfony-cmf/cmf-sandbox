<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use PHPCR\RepositoryInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use PHPCR\Util\NodeHelper;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Cmf\Bundle\MenuBundle\Document\MenuItem;
use Symfony\Cmf\Bundle\MenuBundle\Document\MultilangMenuItem;

class LoadMenuData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param \Doctrine\ODM\PHPCR\DocumentManager $dm
     */
    public function load(ObjectManager $dm)
    {
        $session = $dm->getPhpcrSession();

        $basepath = $this->container->getParameter('symfony_cmf_menu.menu_basepath');
        $content_path = $this->container->getParameter('symfony_cmf_content.content_basepath');

        NodeHelper::createPath($session, $basepath);
        $root = $dm->find(null, $basepath);

        /** @var $menuitem MenuItem */
        $main = $this->createMenuItem($dm, $root, 'main', array('en' => 'Home', 'de' => 'Start', 'fr' => 'Accueil'), $dm->find(null, "$content_path/home"));
        $main->setChildrenAttributes(array("class" => "menu_main"));

        if ($session->getRepository()->getDescriptor(RepositoryInterface::QUERY_FULL_TEXT_SEARCH_SUPPORTED)) {
            $this->createMenuItem($dm, $main, 'search-item', 'Search', null, null, 'search');
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
        } else if (null !== $uri) {
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
}
