<?php

namespace BF13\Bundle\BusinessApplicationBundle\DependencyInjection;

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

        $rootNode = $treeBuilder->root('bf13_business_application');
        $rootNode
            ->children()
                ->arrayNode('api')
                    ->children()
                        ->scalarNode('url')->isRequired()->end()
                        ->scalarNode('token')->isRequired()->end()
                        ->scalarNode('auth')->defaultValue('')->end()
                        ->scalarNode('workdir')->defaultValue('%kernel.cache_dir%/bf13')->end()
                        ->scalarNode('targetdir')->defaultValue('%kernel.root_dir%/..')->end()
                    ->end()
                ->end()
                ->scalarNode('docdir')->defaultValue('%kernel.cache_dir%/documents')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
