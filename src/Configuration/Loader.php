<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use Kiboko\Plugin\Sylius\Validator\ApiType;
use Kiboko\Plugin\Sylius\Validator\LoaderConfigurationValidator;
use Symfony\Component\Config;

final class Loader implements Config\Definition\ConfigurationInterface
{

    public function getConfigTreeBuilder(): \Symfony\Component\Config\Definition\Builder\TreeBuilder
    {
        $builder = new Config\Definition\Builder\TreeBuilder('loader');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->children()
                ->scalarNode('api_type')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->always(fn(string $item) => LoaderConfigurationValidator::validateApiType($item))
                    ->end()
                ->end()
                ->scalarNode('type')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->always(fn(string $item) => LoaderConfigurationValidator::validateType($item))
                    ->end()
                ->end()
                ->scalarNode('method')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->always(fn(string $item) => LoaderConfigurationValidator::validateMethod($item))
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
