<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Capacity;

use Kiboko\Plugin\Sylius;
use Kiboko\Plugin\Sylius\Validator\ApiType;
use PhpParser\Builder;
use PhpParser\Node;

final class Upsert implements CapacityInterface
{
    private static array $endpointsLegacy = [
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

    private static array $endpointsAdmin = [
        // Core Endpoints
        'administrator',
        'catalogPromotion',
        'customerGroup',
        'exchangeRate',
        'product',
        'productAssociationType',
        'productOption',
        'productReview',
        'productVariant',
        'province',
        'shippingCategory',
        'shippingMethod',
        'taxCategory',
        'taxon',
        'zone',
    ];

    private static array $endpointsShop = [
        // Core Endpoints
        'address',
        'customer',
        'order',
    ];

    public function applies(array $config): bool
    {
        if (!isset($config['api_type'])) {
            return false;
        }
        $endpoints = match ($config['api_type']) {
            'admin' => self::$endpointsAdmin,
            'shop' => self::$endpointsShop,
            'legacy' => self::$endpointsLegacy,
            default => throw new \InvalidArgumentException(sprintf('The value of api_type should be one of [%s], got %s.', implode(', ', ApiType::casesValue()), json_encode($config['api_type'], \JSON_THROW_ON_ERROR)))
        };

        return isset($config['type'])
            && \in_array($config['type'], $endpoints)
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
