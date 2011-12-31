<?php

namespace Sandbox\MainBundle\Resources\data\fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Symfony\Cmf\Bundle\ChainRoutingBundle\Document\Route;

class LoadRoutingData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    protected $session;

    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->session = $this->container->get('doctrine_phpcr.default_session'); // FIXME: should get this from manager in load, not necessarily the default
    }

    public function getOrder() {
        return 21;
    }

    /**
     * Load routing data into the document manager.
     *
     * NOTE: We demo all possibilities. Of course, you should try to be
     * consistent in what you use and only use different things for special
     * cases.
     *
     * @param $dm
     */
    public function load($dm)
    {
        $base_path    = $this->container->getParameter('symfony_cmf_core.routing_basepath');
        $content_path = $this->container->getParameter('symfony_cmf_content.static_basepath');

        if ($this->session->itemExists($base_path)) {
            $this->session->removeItem($base_path);
        }
        $this->createPath(dirname($base_path));
        $parent = $dm->find(null, dirname($base_path));

        // explicit controller
        $home = new Route;
        $home->setPosition($parent, basename($base_path));
        $home->setRouteContent($dm->find(null, "$content_path/home"));
        $home->setController('sandbox_main.controller:homepageAction');
        $dm->persist($home);

        // alias to controller mapping
        $company = new Route;
        $company->setPosition($home, 'company');
        $company->setRouteContent($dm->find(null, "$content_path/company"));
        $company->setControllerAlias('static_pages');
        $dm->persist($company);

        // class to controller mapping
        $team = new Route;
        $team->setPosition($company, 'team');
        $team->setRouteContent($dm->find(null, "$content_path/company_team"));
        $dm->persist($team);

        // explicit template
        $more = new Route;
        $more->setPosition($company, 'more');
        $more->setRouteContent($dm->find(null, "$content_path/company_more"));
        $more->setTemplate('SandboxMainBundle:EditableStaticContent:nosidebar.html.twig');
        $dm->persist($more);

        $projects = new Route;
        $projects->setPosition($home, 'projects');
        $projects->setRouteContent($dm->find(null, "$content_path/projects"));
        $projects->setControllerAlias('static_pages');
        $dm->persist($projects);

        $cmf = new Route;
        $cmf->setPosition($projects, 'cmf');
        $cmf->setRouteContent($dm->find(null, "$content_path/projects_cmf"));
        $cmf->setControllerAlias('static_pages');
        $dm->persist($cmf);

        $dm->flush();
    }

    /**
     * Create a node and it's parents, if necessary.  Like mkdir -p.
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
