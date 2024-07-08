<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Contract\Configurator\PluginConfigurationInterface;
use Kiboko\Plugin\Sylius\Validator\ExtractorConfigurationValidator;
use Kiboko\Plugin\Sylius\Validator\LoaderConfigurationValidator;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class Configuration implements PluginConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $client = new Configuration\Client();
        $extractor = new Configuration\Extractor();
        $loader = new Configuration\Loader();

        $builder = new TreeBuilder('sylius');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->validate()
                ->ifTrue(fn (array $value) => \array_key_exists('extractor', $value) && \array_key_exists('loader', $value))
                ->thenInvalid('Your configuration should either contain the "extractor" or the "loader" key, not both.')
            ->end()
            ->validate()
                ->always(function (array $value) {
                    if (\array_key_exists('extractor', $value)) {
                        ExtractorConfigurationValidator::validate($value['extractor']['type'], $value['extractor']['method'], $value['version']);
                    }

                    if (\array_key_exists('loader', $value)) {
                        LoaderConfigurationValidator::validate($value['loader']['type'], $value['loader']['method'], $value['version']);
                    }

                    return $value;
                })
            ->end()
            ->children()
                ->arrayNode('expression_language')
                    ->scalarPrototype()->end()
                ->end()
                ->scalarNode('version')
                    ->isRequired()
                    ->validate()
                        ->ifNotInArray(['admin', 'shop'])
                        ->thenInvalid('Invalid version %s')
                    ->end()
                ->end()
                ->append(node: $extractor->getConfigTreeBuilder()->getRootNode())
                ->append(node: $loader->getConfigTreeBuilder()->getRootNode())
                ->append(node: $client->getConfigTreeBuilder()->getRootNode())
            ->end()
        ;

        return $builder;
    }
}
