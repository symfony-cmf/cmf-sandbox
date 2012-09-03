<?php

namespace Sandbox\MainBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sandbox_main');

        $rootNode
            ->children()
                ->scalarNode('use_sonata_admin')
                    ->defaultValue('auto')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_bool($v) && $v !== 'auto';
                        })
                        ->thenInvalid("This configuration allows only the values true, false or 'auto'")
                    ->end()
                ->end()
                ->scalarNode('content_basepath')->defaultNull()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
