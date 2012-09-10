<?php
namespace Sandbox\MainBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
//TODO: make config xml instead of yml
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;


class SandboxMainExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        //$loader->load('config.yml');
        $loader->load('services.yml');

        if ($config['use_sonata_admin']) {
            $this->loadSonataAdmin($config, $loader, $container);
        }
    }

    public function loadSonataAdmin($config, YamlFileLoader $loader, ContainerBuilder $container)
    {
        if ('auto' === $config['use_sonata_admin'] && !class_exists('Sonata\\DoctrinePHPCRAdminBundle\\SonataDoctrinePHPCRAdminBundle')) {
            return;
        }

        $loader->load('main-admin.yml');
        $contentBasepath = $config['content_basepath'];
        if (null === $contentBasepath) {
            if ($container->hasParameter('symfony_cmf_core.content_basepath')) {
                $contentBasepath = $container->getParameter('symfony_cmf_core.content_basepath');
            } else {
                $contentBasepath = '/cms/content';
            }
        }
        $container->setParameter($this->getAlias() . '.content_basepath', $contentBasepath);
    }
}
