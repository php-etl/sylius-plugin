<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use Symfony\Component\Config;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;

final class Search implements Config\Definition\ConfigurationInterface
{
    public function getConfigTreeBuilder(): \Symfony\Component\Config\Definition\Builder\TreeBuilder
    {
        $builder = new Config\Definition\Builder\TreeBuilder('search');

        /* @phpstan-ignore-next-line */
        return $builder->getRootNode()
            ->arrayPrototype()
                ->children()
                    ->scalarNode('field')->cannotBeEmpty()->isRequired()->end()
                    ->scalarNode('operator')->cannotBeEmpty()->isRequired()->end()
                    ->variableNode('value')
                        ->cannotBeEmpty()
                        ->isRequired()
                        ->validate()
                            ->ifTrue(isExpression())
                            ->then(asExpression())
                        ->end()
                    ->end()
                    ->scalarNode('scope')
                        ->cannotBeEmpty()
                        ->validate()
                            ->ifTrue(isExpression())
                            ->then(asExpression())
                        ->end()
                    ->end()
                    ->scalarNode('locale')
                        ->cannotBeEmpty()
                        ->validate()
                            ->ifTrue(isExpression())
                            ->then(asExpression())
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
