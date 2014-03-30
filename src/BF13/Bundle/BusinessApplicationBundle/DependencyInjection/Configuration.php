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
                ->scalarNode('api_url')->isRequired()->end()
                ->scalarNode('api_call')->isRequired()->end()
                ->scalarNode('api_auth')->end()
                ->scalarNode('api_workdir')->defaultValue('%kernel.cache_dir%')->end()
                ->scalarNode('api_extractdir')->defaultValue('%kernel.root_dir%/..')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
