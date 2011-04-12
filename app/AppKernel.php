<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),

            // enable third-party bundles
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Liip\FunctionalTestBundle\LiipFunctionalTestBundle(),

            // enable cmf bundles
            new Symfony\Bundle\DoctrinePHPCRBundle\DoctrinePHPCRBundle(),
            new Symfony\Cmf\Bundle\CoreBundle\SymfonyCmfCoreBundle(),
            new Symfony\Cmf\Bundle\NavigationBundle\SymfonyCmfNavigationBundle(),

            // and the sandbox bundle
            new Sandbox\MainBundle\SandboxMainBundle(),
        );

        if ($this->isDebug()) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        // use YAML for configuration
        // comment to use another configuration format
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');

        // uncomment to use XML for configuration
        //$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.xml');

        // uncomment to use PHP for configuration
        //$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.php');
    }

    public function registerRootDir()
    {
        return __DIR__;
    }
}
