<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use Kiboko\Plugin\Sylius\Validator\ExtractorConfigurationValidator;
use Symfony\Component\Config;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;

final class Extractor implements Config\Definition\ConfigurationInterface
{


    public function getConfigTreeBuilder(): \Symfony\Component\Config\Definition\Builder\TreeBuilder
    {
        $filters = new Search();

        $builder = new Config\Definition\Builder\TreeBuilder('extractor');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->validate()
            ->ifArray()
            ->then(function (array $item) {
                return ExtractorConfigurationValidator::validate($item);
            })
            ->end()
            ->children()
                ->scalarNode('api_type')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('type')
                    ->isRequired()
                ->end()
                ->scalarNode('method')->end()
                ->scalarNode('code')
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
