<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),

            // enable cmf bundles
            new Symfony\Cmf\Bundle\RoutingExtraBundle\SymfonyCmfRoutingExtraBundle(),
            new Symfony\Cmf\Bundle\CoreBundle\SymfonyCmfCoreBundle(),
            new Symfony\Cmf\Bundle\MultilangContentBundle\SymfonyCmfMultilangContentBundle(),
            new Symfony\Cmf\Bundle\MenuBundle\SymfonyCmfMenuBundle(),
            new Symfony\Cmf\Bundle\ContentBundle\SymfonyCmfContentBundle(),
            new Symfony\Cmf\Bundle\TreeBundle\SymfonyCmfTreeBundle(),
            new Symfony\Cmf\Bundle\PHPCRBrowserBundle\SymfonyCmfPHPCRBrowserBundle(),
            new Symfony\Cmf\Bundle\BlockBundle\SymfonyCmfBlockBundle(),

            new Liip\VieBundle\LiipVieBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),

            // and the sandbox bundle
            new Sandbox\MainBundle\SandboxMainBundle(),
            new Sandbox\TestBundle\SandboxTestBundle(),
            new Sandbox\AdminBundle\SandboxAdminBundle(),
            new Sandbox\BlockBundle\SandboxBlockBundle(),

            // admin bundle
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrinePHPCRAdminBundle\SonataDoctrinePHPCRAdminBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();

            // additional bundle for tests
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
