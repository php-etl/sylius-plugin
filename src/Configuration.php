<?php declare(strict_types=1);

namespace Kiboko\Plugin\Sylius;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $client = new Configuration\Client();
        $extractor = new Configuration\Extractor();
        $loader = new Configuration\Loader();

        $builder = new TreeBuilder('sylius');

        /** @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->validate()
                ->ifTrue(function (array $value) {
                    return array_key_exists('extractor', $value) && array_key_exists('loader', $value);
                })
                ->thenInvalid('Your configuration should either contain the "extractor" or the "loader" key, not both.')
            ->end()
            ->children()
                ->append(node: $extractor->getConfigTreeBuilder()->getRootNode())
                ->append(node: $loader->getConfigTreeBuilder()->getRootNode())
                ->append(node: $client->getConfigTreeBuilder()->getRootNode())
            ->end()
        ;

        return $builder;
    }
}
