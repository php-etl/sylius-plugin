<?php declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Capacity;

use Kiboko\Plugin\Sylius;
use PhpParser\Builder;
use PhpParser\Node;

final class Upsert implements CapacityInterface
{
    private static $endpoints = [
        // Core Endpoints
        'carts',
        'channels',
        'countries',
        'carts',
        'channels',
        'countries',
        'currencies',
        'customers',
        'exchangeRates',
        'locales',
        'orders',
        'paymentMethods',
        'payments',
        'products',
        'productAttributes',
        'productAssociationTypes',
        'productOptions',
        'productReviews',
        'productVariants',
        'promotions',
        'promotionCoupons',
        'shipments',
        'shippingCategories',
        'taxCategories',
        'taxRates',
        'taxons',
        'users',
        'zones',
    ];

    public function applies(array $config): bool
    {
        return isset($config['type'])
            && in_array($config['type'], self::$endpoints)
            && isset($config['method'])
            && $config['method'] === 'upsert';
    }

    public function getBuilder(array $config): Builder
    {
        $builder = (new Sylius\Builder\Capacity\Update())
            ->withEndpoint(endpoint: new Node\Identifier(sprintf('get%sApi', ucfirst($config['type']))))
            ->withCode(code: new Node\Expr\ArrayDimFetch(
                var: new Node\Expr\Variable('line'),
                dim: new Node\Scalar\String_('code')
            ))
            ->withData(line: new Node\Expr\Variable('line'));

        return $builder;
    }
}
