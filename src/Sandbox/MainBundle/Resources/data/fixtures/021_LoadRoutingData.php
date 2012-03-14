<?php

namespace Sandbox\MainBundle\Resources\data\fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Symfony\Cmf\Bundle\ChainRoutingBundle\Document\Route;
use Symfony\Cmf\Bundle\ChainRoutingBundle\Document\RedirectRoute;
use Symfony\Cmf\Bundle\MultilangContentBundle\Document\MultilangLanguageSelectRoute;

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
    public function load(ObjectManager $dm)
    {
        $base_path    = $this->container->getParameter('symfony_cmf_chain_routing.routing_repositoryroot');
        $content_path = $this->container->getParameter('symfony_cmf_content.static_basepath');

        if ($this->session->itemExists($base_path)) {
            $this->session->removeItem($base_path);
        }
        $this->createPath(dirname($base_path));
        $root = $dm->find(null, dirname($base_path));
        $locales = array('en', 'fr', 'de'); //TODO: can we get this from phpcr-odm in a sane way?

        $parent = new MultilangLanguageSelectRoute();
        $parent->setPosition($root, basename($base_path));
        $dm->persist($parent);

        foreach($locales as $locale) {
            $home = new Route;
            $home->setPosition($parent, $locale);
            $home->setLocale($locale);
            $home->setRouteContent($dm->find(null, "$content_path/home"));
            $dm->persist($home);

            $company = new Route;
            $company->setPosition($home, 'company');
            $company->setLocale($locale);
            $company->setRouteContent($dm->find(null, "$content_path/company"));
            $dm->persist($company);

            $team = new Route;
            $team->setPosition($company, 'team');
            $team->setLocale($locale);
            $team->setRouteContent($dm->find(null, "$content_path/company_team"));
            $dm->persist($team);

            $more = new Route;
            $more->setPosition($company, 'more');
            $more->setLocale($locale);
            $more->setRouteContent($dm->find(null, "$content_path/company_more"));
            $dm->persist($more);

            $projects = new Route;
            $projects->setPosition($home, 'projects');
            $projects->setLocale($locale);
            $projects->setRouteContent($dm->find(null, "$content_path/projects"));
            $dm->persist($projects);

            $cmf = new Route;
            $cmf->setPosition($projects, 'cmf');
            $cmf->setLocale($locale);
            $cmf->setRouteContent($dm->find(null, "$content_path/projects_cmf"));
            $dm->persist($cmf);
        }

        // demo features of routing

        // we can create routes without locales, but will lose the language context of course

        $demo = new Route;
        $demo->setPosition($parent, 'demo');
        $demo->setRouteContent($dm->find(null, "$content_path/demo"));
        $demo->setTemplate('SandboxMainBundle:Demo:template_explicit.html.twig');
        $dm->persist($demo);

        // explicit template
        $template = new Route;
        $template->setPosition($demo, 'atemplate');
        $template->setRouteContent($dm->find(null, "$content_path/demo_template"));
        $template->setTemplate('SandboxMainBundle:Demo:template_explicit.html.twig');
        $dm->persist($template);

        // explicit controller
        $controller = new Route;
        $controller->setPosition($demo, 'controller');
        $controller->setRouteContent($dm->find(null, "$content_path/demo_controller"));
        $controller->setController('sandbox_main.controller:specialAction');
        $dm->persist($controller);

        // alias to controller mapping
        $alias = new Route;
        $alias->setPosition($demo, 'alias');
        $alias->setRouteContent($dm->find(null, "$content_path/demo_alias"));
        $alias->setControllerAlias('demo_alias');
        $dm->persist($alias);

        // class to controller mapping
        $class = new Route;
        $class->setPosition($demo, 'class');
        $class->setRouteContent($dm->find(null, "$content_path/demo_class"));
        $dm->persist($class);

        // redirections

        // redirect to uri
        $redirect = new RedirectRoute();
        $redirect->setPosition($parent, 'external');
        $redirect->setUri('http://cmf.symfony.com');
        $dm->persist($redirect);

        // redirect to other doctrine route
        $redirectRoute = new RedirectRoute();
        $redirectRoute->setPosition($parent, 'short');
        $redirectRoute->setRouteTarget($cmf);
        $dm->persist($redirectRoute);

        // redirect to Symfony route
        $redirectS = new RedirectRoute();
        $redirectS->setPosition($parent, 'short1');
        $redirectS->setRouteName('test');
        $dm->persist($redirectS);

        // class to template mapping is used for all the rest

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
