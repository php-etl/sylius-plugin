<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;
use Symfony\Component\Config;

final class Search implements Config\Definition\ConfigurationInterface
{
    public function getConfigTreeBuilder()
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
            ->end();
    }
}
