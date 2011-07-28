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
    protected $translator;

    protected $dm;

    protected $navigationdocument;

    protected $session;

    protected $container;

    protected $allowed_languages = array('en', 'de');

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->navigationdocument = $this->container->getParameter('symfony_cmf_navigation.document');
        $this->session = $this->container->get('doctrine_phpcr.default_session'); // FIXME: should get this from manager in load, not necessarily the default
        $this->translator = $this->container->get($this->container->getParameter('symfony_cmf_multilang_content.translator'));
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

        $this->createNavigationItem($base_path, array('de'=>'Start', 'en'=>'Home'), 'static_pages', "$content_path/home");
        $this->createNavigationItem("$base_path/company", array('en'=>'The Company', 'de'=>'Die Firma'), 'static_pages', "$content_path/company");
        $this->createNavigationItem("$base_path/company/team", array('en'=>'Our Team', 'de'=>'Unser Team'), 'static_pages', "$content_path/company_team");
        $this->createNavigationItem("$base_path/company/more", array('en'=>'Other information', 'de'=>'Mehr Informationen'), 'static_pages', "$content_path/company_more");
        $this->createNavigationItem("$base_path/projects", array('en'=>'Our Projects','de'=>'Unsere Projekte'), 'static_pages', "$content_path/projects");
        $this->createNavigationItem("$base_path/projects/cmf", array('en'=>'Symfony Cmf'), 'static_pages', "$content_path/projects_cmf");

        $this->dm->flush();
    }

    /**
     * @return a Navigation instance with the specified information
     */
    protected function createNavigationItem($path, $label, $controller, $referenced_path)
    {
        if (!is_array($label)) {
            throw new \Exception("Invalid multilingual label for node '$path'");
        }

        // Remove the node if it already exists
        if ($old_node = $this->dm->find($this->navigationdocument, $path)) {
            $this->dm->remove($old_node);
        }

        // Get the referenced node
        $ref_node = $this->session->getNode($referenced_path);
        // TODO: when phpcr-odm supports references, we can assign the document instead of the node

        // Create the navigation node
        $navigation = new $this->navigationdocument();
        $navigation->setPath($path);
        $navigation->setController($controller);

        foreach($label as $lang => $value) {
            $navigation->setLabel($value);
            $navigation->lang = $lang;
            $this->translator->persistTranslation($navigation);
        }

        $navigation->setReference($ref_node);
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
