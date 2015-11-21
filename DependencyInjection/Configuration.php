<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 20.11.15
 * Time: 20:50
 */

namespace AxS\ConfigBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('axs_config');

        $rootNode
            ->children()
                ->booleanNode('use_groups')
                    ->defaultTrue()
                ->end()
            ->end();

        return $treeBuilder;
    }
}