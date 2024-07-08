<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use Symfony\Component\Config;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;

final class Client implements Config\Definition\ConfigurationInterface
{
    public function getConfigTreeBuilder(): Config\Definition\Builder\TreeBuilder
    {
        $builder = new Config\Definition\Builder\TreeBuilder('client');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->validate()
                ->ifTrue(fn ($value) => !empty($value['token']) && (!empty($value['username']) || !empty($value['password'])))
                ->thenInvalid('You cannot specify both a token and a username/password combination.')
            ->end()
            ->validate()
                ->ifTrue(fn ($value) => (!empty($value['username']) && empty($value['password'])) || (empty($value['username']) && !empty($value['password'])))
                ->thenInvalid('Both username and password must be defined together.')
            ->end()
            ->validate()
                ->ifTrue(fn ($value) => empty($value['token']) && (empty($value['username']) || empty($value['password'])))
                ->thenInvalid('You must specify either a token or a username and password combination.')
            ->end()
            ->children()
                ->scalarNode('api_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('username')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('password')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('token')
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
