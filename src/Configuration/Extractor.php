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
                ->always(fn (array $item) => ExtractorConfigurationValidator::validate($item)) // store the item value
            ->end()
            ->children()
                ->scalarNode('api_type')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->always(fn (string $item) => ExtractorConfigurationValidator::validateApiType($item)) // check index of the item value
                    ->end()
                ->end()
                ->scalarNode('type')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->always(fn (string $item) => ExtractorConfigurationValidator::validateType($item)) // check index of the item value
                    ->end()
                ->end()
                ->scalarNode('method')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->always(fn (string $item) => ExtractorConfigurationValidator::validateMethod($item)) // check index of the item value
                    ->end()
                ->end()
                ->scalarNode('code')
                    ->cannotBeEmpty()
                    ->validate()
                        ->always(fn (string $item) => ExtractorConfigurationValidator::validateCode($item)) // check index of the item value
                    ->end()
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
