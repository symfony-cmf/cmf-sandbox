<?php

namespace Sandbox\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('sandbox_media')
            ->children()
                ->scalarNode('media_basepath')->defaultValue('/cms/media')->end()
                ->scalarNode('gallery_basepath')->defaultValue('/cms/media_gallery')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
