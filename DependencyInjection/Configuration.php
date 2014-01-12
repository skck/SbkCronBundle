<?php

namespace Sbk\Bundle\CronBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Sbk\Bundle\CronBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sbk_cron');

        $rootNode
            ->children()
                ->arrayNode('tasks')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('bin')->defaultValue('php')->end()
                            ->scalarNode('script')->end()
                            ->scalarNode('command')->end()
                            ->scalarNode('expression')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
