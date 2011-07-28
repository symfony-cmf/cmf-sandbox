<?php

namespace Sandbox\MainBundle\Resources\data\fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Bundle\DoctrinePHPCRBundle\JackalopeLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Liipch\CoreBundle\Helper\NodeHelper;
use Liipch\CoreBundle\Document\Navigation;


class LoadNavigationData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    protected $dm;

    protected $navigationdocument;

    protected $session;

    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->navigationdocument = $this->container->getParameter('symfony_cmf_navigation.document');
        $this->session = $this->container->get('doctrine_phpcr.default_session'); // FIXME: should get this from manager in load, not necessarily the default
    }

    public function getOrder() {
        return 20;
    }

    public function load($manager)
    {
        $this->dm = $manager;

        $base_path    = $this->container->getParameter('symfony_cmf_core.mainmenu_basepath');
        $content_path = $this->container->getParameter('symfony_cmf_content.static_basepath');

        $this->createPath(dirname($base_path));

        // TODO: Fix the nodes references
        $this->createContentNavigationNode($base_path, 'Home', 'static_pages', "$content_path/page");
        $this->createContentNavigationNode("$base_path/company", 'The Company', "static_pages", "$content_path/page");
        $this->createContentNavigationNode("$base_path/company/team", "Our Team", "static_pages", "$content_path/other");
        $this->createContentNavigationNode("$base_path/company/more", "Other information", "static_pages", "$content_path/other");
        $this->createContentNavigationNode("$base_path/projects", 'Our Projects', "static_pages", "$content_path/page");
        $this->createContentNavigationNode("$base_path/projects/cmf", "Symfony Cmf", "static_pages", "$content_path/other");
        /*
        $this->createNavigationNode("$base_path/das_ist_liip/jobs", array('de' => "Jobs", 'fr' => "Jobs"), "job_list", "$jobs_path/overview");
        $this->createNavigationNode("$base_path/das_ist_liip/kontakt", array('de' => "Kontakt", 'fr' => "Contact"), "static_pages", "$content_path/staticpage");

        $this->createNavigationNode("$base_path/das_machen_wir", array('de' => "Das machen wir", 'fr' => "Ce que nous faisons"), "static_pages", "$content_path/staticpage");
        $this->createNavigationNode("$base_path/das_machen_wir/projekte", array('de' => "Projekte", 'fr' => "Projets"), "static_pages", "$content_path/staticpage");
        $this->createNavigationNode("$base_path/das_machen_wir/events", array('de' => "Events", 'fr' => "Evènements"), "static_pages", "$content_path/staticpage");
        $this->createNavigationNode("$base_path/das_machen_wir/news", array('de' => "News", 'fr' => "News"), "static_pages", "$content_path/staticpage");

        $this->createNavigationNode("$base_path/so_arbeiten_wir", array('de' => "So arbeiten wir", 'fr' => "Notre façon de travailler"), "static_pages", "$content_path/staticpage");
        $this->createNavigationNode("$base_path/so_arbeiten_wir/scrum", array('de' => "Scrum", 'fr' => "Scrum"), "static_pages", "$content_path/staticpage");
        $this->createNavigationNode("$base_path/so_arbeiten_wir/niwea", array('de' => "Niwea", 'fr' => "Niwea"), "static_pages", "$content_path/staticpage");
        $this->createNavigationNode("$base_path/so_arbeiten_wir/open_source", array('de' => "Open source", 'fr' => "Open source"), "static_pages",  "$content_path/staticpage");
        */

        $this->dm->flush();
    }

    /**
     * @return a Navigation instance with the specified information
     */
    protected function createContentNavigationNode($path, $label, $controller, $referenced_path)
    {
        // Remove the node if it already exists
        if ($old_node = $this->dm->find($this->navigationdocument, $path)) {
            $this->dm->remove($old_node);
            /* needed?
            $this->dm->flush();
            $this->dm->clear();
            */
        }

        // Get the referenced node
        $ref_node = $this->session->getNode($referenced_path);

        // Create the navigation node
        $navi = new $this->navigationdocument();
        $navi->setPath($path);
        $navi->setLabel($label);
        $navi->setController($controller);

        // The node must be persisted once so that the internal $navi property is populated
        $this->dm->persist($navi);

        // must flush so the internal node property is populated. can go away once the reference annotation is implemented
        $this->dm->flushNoSave();

        $navi->setReference($ref_node);
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
