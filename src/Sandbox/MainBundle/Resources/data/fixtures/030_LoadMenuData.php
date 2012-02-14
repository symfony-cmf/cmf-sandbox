<?php

namespace Symfony\Cmf\Bundle\MenuBundle\Resources\data\fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Bundle\PHPCRBundle\JackalopeLoader;
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

    public function load($manager)
    {
        $this->dm = $manager;

        $base_path = $this->container->getParameter('symfony_cmf_menu.menu_basepath');
        $content_path = $this->container->getParameter('symfony_cmf_content.static_basepath');

        $this->createPath($base_path);

        // REMEMBER: all menu items must be named -item !
        $menuitem = $this->createMenuItem("$base_path/main", 'Main menu', array('en' => 'Home', 'de' => 'Start', 'fr' => 'Acceuil'), $this->dm->find(null, "$content_path/home"));
        $menuitem->setAttributes(array("class" => "menu_main"));

        $this->createMenuItem("$base_path/main/admin-item", 'Adminitem', 'Admin', null, null, 'sonata_admin_dashboard');

        $this->createMenuItem("$base_path/main/projects-item", 'Projectsitem', array('en' => 'Projects', 'de' => 'Projekte', 'fr' => 'Projets'), $this->dm->find(null, "$content_path/projects"));
        $this->createMenuItem("$base_path/main/projects-item/cmf-item", 'Cmfitem', 'Symfony CMF', $this->dm->find(null, "$content_path/projects_cmf"));

        $this->createMenuItem("$base_path/main/company-item", 'Companyitem', array('en' => 'Company', 'de' => 'Firma', 'fr' => 'Entreprise'), $this->dm->find(null, "$content_path/company"));
        $this->createMenuItem("$base_path/main/company-item/team-item", 'Teamitem', array('en' => 'Team', 'de' => 'Team', 'fr' => 'Equipe'), $this->dm->find(null, "$content_path/company_team"));
        $this->createMenuItem("$base_path/main/company-item/more-item", 'Moreitem', array('en' => 'More', 'de' => 'Mehr', 'fr' => 'Plus'), $this->dm->find(null, "$content_path/company_more"));

        $this->createMenuItem("$base_path/main/demo-item", 'Demoitem', 'Demo', $this->dm->find(null, "$content_path/demo"));
        //TODO: this should be possible without a content as the controller might not need a content. support directly having the route document as "content" in the menu document?
        $this->createMenuItem("$base_path/main/demo-item/controller-item", 'Controlleritem', 'Explicit controller', $this->dm->find(null, "$content_path/demo_controller"));
        $this->createMenuItem("$base_path/main/demo-item/template-item", 'Templateitem', 'Explicit template', $this->dm->find(null, "$content_path/demo_template"));
        $this->createMenuItem("$base_path/main/demo-item/alias-item", 'Aliasitem', 'Route alias to controller', $this->dm->find(null, "$content_path/demo_alias"));
        $this->createMenuItem("$base_path/main/demo-item/class-item", 'Classitem', 'Class to controller', $this->dm->find(null, "$content_path/demo_class"));
        $this->createMenuItem("$base_path/main/demo-item/test-item", 'Testitem', 'Normal Symfony Route', null, null, 'test');
        $this->createMenuItem("$base_path/main/demo-item/external-item", 'ExternalLinkItem', 'External Link', null, 'http://cmf.symfony.com/');

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

        $menuitem = is_array($label) ? new MultilangMenuItem() : new MenuItem();
        $menuitem->setPath($path);
        $this->dm->persist($menuitem); // do persist before binding translation

        $menuitem->setName($name);
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
