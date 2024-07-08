<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Capacity;

use Kiboko\Plugin\Sylius;
use PhpParser\Builder;
use PhpParser\Node;

final class Upsert implements CapacityInterface
{
    public function applies(array $config): bool
    {
        $endpoints = [...Sylius\Validator\ExtractorConfigurationValidator::ADMIN_VALID_TYPES, ...Sylius\Validator\ExtractorConfigurationValidator::SHOP_VALID_TYPES];

        return isset($config['type'])
            && \array_key_exists($config['type'], $endpoints)
            && isset($config['method'])
            && 'upsert' === $config['method'];
    }

    public function getBuilder(array $config): Builder
    {
        return (new Sylius\Builder\Capacity\Upsert())
            ->withEndpoint(endpoint: new Node\Identifier(sprintf('get%sApi', ucfirst((string) $config['type']))))
            ->withCode(code: new Node\Expr\ArrayDimFetch(
                var: new Node\Expr\Variable('line'),
                dim: new Node\Scalar\String_('code')
            ))
            ->withData(line: new Node\Expr\Variable('line'))
        ;
    }
}
