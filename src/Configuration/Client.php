<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use Symfony\Component\Config;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;

final class Client implements Config\Definition\ConfigurationInterface
{
    public function getConfigTreeBuilder(): \Symfony\Component\Config\Definition\Builder\TreeBuilder
    {
        $builder = new Config\Definition\Builder\TreeBuilder('client');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->validate()
                ->ifTrue(fn($v) => !empty($v['token']) && (!empty($v['username']) || !empty($v['password'])))
                ->thenInvalid('You cannot specify both a token and a username/password combination.')
            ->end()
            ->validate()
                ->ifTrue(fn($v) => (!empty($v['username']) && empty($v['password'])) || (empty($v['username']) && !empty($v['password'])))
                ->thenInvalid('Both username and password must be defined together.')
            ->end()
            ->validate()
                ->ifTrue(fn($v) => empty($v['token']) && (empty($v['username']) || empty($v['password'])))
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
