<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('hubertinio_sylius_apaczka_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
