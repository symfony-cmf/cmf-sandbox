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

    public function load($dm)
    {
        $base_path    = $this->container->getParameter('symfony_cmf_core.routing_basepath');
        $content_path = $this->container->getParameter('symfony_cmf_content.static_basepath');

        if ($this->session->itemExists($base_path)) {
            $this->session->removeItem($base_path);
        }
        $this->createPath(dirname($base_path));
        $parent = $dm->find(null, dirname($base_path));

        $home = new Route;
        $home->setPosition($parent, basename($base_path));
        $home->setReference($dm->find(null, "$content_path/home"));
        $home->setControllerAlias('static_pages');
        $dm->persist($home);

        $company = new Route;
        $company->setPosition($home, 'company');
        $company->setReference($dm->find(null, "$content_path/company"));
        $company->setControllerAlias('static_pages');
        $dm->persist($company);

        $team = new Route;
        $team->setPosition($company, 'team');
        $team->setReference($dm->find(null, "$content_path/team"));
        $team->setControllerAlias('static_pages');
        $dm->persist($team);

        $more = new Route;
        $more->setPosition($company, 'more');
        $more->setReference($dm->find(null, "$content_path/more"));
        $more->setControllerAlias('static_pages');
        $dm->persist($more);

        $projects = new Route;
        $projects->setPosition($home, 'projects');
        $projects->setReference($dm->find(null, "$content_path/projects"));
        $projects->setControllerAlias('static_pages');
        $dm->persist($projects);

        $cmf = new Route;
        $cmf->setPosition($projects, 'cmf');
        $cmf->setReference($dm->find(null, "$content_path/cmf"));
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
