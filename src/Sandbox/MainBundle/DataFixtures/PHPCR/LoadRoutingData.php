<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use PHPCR\Util\NodeHelper;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RedirectRoute;

class LoadRoutingData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 21;
    }

    /**
     * Load routing data into the document manager.
     *
     * NOTE: We demo all possibilities. Of course, you should try to be
     * consistent in what you use and only use different things for special
     * cases.
     *
     * @param $dm \Doctrine\ODM\PHPCR\DocumentManager
     */
    public function load(ObjectManager $dm)
    {
        $session = $dm->getPhpcrSession();

        $basepath = $this->container->getParameter('symfony_cmf_routing_extra.routing_repositoryroot');
        $content_path = $this->container->getParameter('symfony_cmf_content.content_basepath');

        if ($session->itemExists($basepath)) {
            $session->removeItem($basepath);
        }

        NodeHelper::createPath($session, $basepath);
        $parent = $dm->find(null, $basepath);

        $locales = $this->container->getParameter('locales');
        foreach ($locales as $locale) {
            $home = new Route;
            $home->setPosition($parent, $locale);
            $home->setDefault('_locale', $locale);
            $home->setDefault(RouteObjectInterface::TEMPLATE_NAME, 'SandboxMainBundle:Homepage:index.html.twig');
            $home->setRequirement('_locale', $locale);
            $home->setRouteContent($dm->find(null, "$content_path/home"));
            $dm->persist($home);

            $company = new Route;
            $company->setPosition($home, 'company');
            $company->setDefault('_locale', $locale);
            $company->setRequirement('_locale', $locale);
            $company->setRouteContent($dm->find(null, "$content_path/company"));
            $dm->persist($company);

            $team = new Route;
            $team->setPosition($company, 'team');
            $team->setDefault('_locale', $locale);
            $team->setRequirement('_locale', $locale);
            $team->setRouteContent($dm->find(null, "$content_path/team"));
            $dm->persist($team);

            $more = new Route;
            $more->setPosition($company, 'more');
            $more->setDefault('_locale', $locale);
            $more->setRequirement('_locale', $locale);
            $more->setRouteContent($dm->find(null, "$content_path/more"));
            $dm->persist($more);

            $projects = new Route;
            $projects->setPosition($home, 'projects');
            $projects->setDefault('_locale', $locale);
            $projects->setRequirement('_locale', $locale);
            $projects->setRouteContent($dm->find(null, "$content_path/projects"));
            $dm->persist($projects);

            $cmf = new Route;
            $cmf->setPosition($projects, 'cmf');
            $cmf->setDefault('_locale', $locale);
            $cmf->setRequirement('_locale', $locale);
            $cmf->setRouteContent($dm->find(null, "$content_path/cmf"));
            $dm->persist($cmf);
        }

        // demo features of routing

        // we can create routes without locales, but will lose the language context of course

        $demo = new Route;
        $demo->setPosition($parent, 'demo');
        $demo->setRouteContent($dm->find(null, "$content_path/demo"));
        $demo->setDefault(RouteObjectInterface::TEMPLATE_NAME, 'SandboxMainBundle:Demo:template_explicit.html.twig');
        $dm->persist($demo);

        // explicit template
        $template = new Route;
        $template->setPosition($demo, 'atemplate');
        $template->setRouteContent($dm->find(null, "$content_path/demo_template"));
        $template->setDefault(RouteObjectInterface::TEMPLATE_NAME, 'SandboxMainBundle:Demo:template_explicit.html.twig');
        $dm->persist($template);

        // explicit controller
        $controller = new Route;
        $controller->setPosition($demo, 'controller');
        $controller->setRouteContent($dm->find(null, "$content_path/demo_controller"));
        $controller->setDefault('_controller', 'sandbox_main.controller:specialAction');
        $dm->persist($controller);

        // alias to controller mapping
        $alias = new Route;
        $alias->setPosition($demo, 'alias');
        $alias->setRouteContent($dm->find(null, "$content_path/demo_alias"));
        $alias->setDefault(RouteObjectInterface::CONTROLLER_ALIAS, 'demo_alias');
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
}
