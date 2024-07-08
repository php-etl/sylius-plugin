<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use Symfony\Component\Config;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;

final class Extractor implements Config\Definition\ConfigurationInterface
{
    public function getConfigTreeBuilder(): Config\Definition\Builder\TreeBuilder
    {
        $filters = new Search();

        $builder = new Config\Definition\Builder\TreeBuilder('extractor');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->children()
                ->scalarNode('type')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('method')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('code')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->append(node: $filters->getConfigTreeBuilder()->getRootNode())
            ->end()
        ;

        return $builder;
    }
}
