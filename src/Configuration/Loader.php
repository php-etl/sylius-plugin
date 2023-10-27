<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use Kiboko\Plugin\Sylius\Validator\LoaderConfigurationValidator;
use Symfony\Component\Config;

final class Loader implements Config\Definition\ConfigurationInterface
{

    public function getConfigTreeBuilder(): \Symfony\Component\Config\Definition\Builder\TreeBuilder
    {
        $builder = new Config\Definition\Builder\TreeBuilder('loader');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->validate()
            ->ifArray()
                ->then(function (array $item) {
                    return LoaderConfigurationValidator::validate($item);
                })
            ->end()
            ->children()
                ->scalarNode('api_type')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('type')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('method')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
