<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Capacity;

use Kiboko\Plugin\Sylius;
use Kiboko\Plugin\Sylius\Validator\ApiType;
use PhpParser\Builder;
use PhpParser\Node;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use function Kiboko\Component\SatelliteToolbox\Configuration\compileValue;

final class All implements CapacityInterface
{
    private static array $endpointsLegacy = [
        // Simple resources Endpoints
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
        'payments',
        'paymentMethods',
        'products',
        'productAttributes',
        'productAssociationTypes',
        'productOptions',
        'promotions',
        'shipments',
        'shippingCategories',
        'taxCategories',
        'taxRates',
        'taxons',
        'users',
        'zones',
    ];

    private static array $endpointsAdmin = [
        // Simple Ressource Endpoints
        'adjustment',
        'administrator',
        'catalogPromotion',
        'channel',
        'country',
        'currency',
        'customerGroup',
        'exchangeRate',
        'locale',
        'order',
        'payment',
        'product',
        'productAssociationType',
        'productImage',
        'productOption',
        'productOptionValue',
        'productReview',
        'productTaxon',
        'productVariant',
        'promotion',
        'province',
        'shipment',
        'shippingCategory',
        'shippingMethod',
        'ShopBillingData',
        'taxCategory',
        'taxon',
        'taxonTranslation',
        'zone',
        'zoneMember',
    ];

    private static array $endpointsShop = [
        // Simple Ressource Endpoints
        'address',
        'adjustment',
        'country',
        'currency',
        'locale',
        'order',
        'orderItem',
        'payment',
        'paymentMethod',
        'product',
        'productReview',
        'productVariant',
        'shipment',
        'shippingMethod',
        'taxon',
    ];

    private static array $doubleEndpointsLegacy = [
        // Double resources Endpoints
        'productReviews',
        'productVariants',
        'promotionCoupons',
    ];

    private static array $doubleEndpointsAdmin = [
        // Double resources Endpoints
        'adjustment',
        'province',
        'shopBillingData',
        'zoneMember',
    ];

    private static array $doubleEndpointsShop = [
        // Double resources Endpoints
        'adjustment',
        'order',
    ];

    public function __construct(private readonly ExpressionLanguage $interpreter) {}

    public function applies(array $config): bool
    {
        switch ($config['api_type']) {
            case 'admin':
                $endpoints = self::$endpointsAdmin;
                $doubleEndpoints = self::$doubleEndpointsAdmin;
                break;
            case 'shop':
                $endpoints = self::$endpointsShop;
                $doubleEndpoints = self::$doubleEndpointsShop;
                break;
            case 'legacy':
                $endpoints = self::$endpointsLegacy;
                $doubleEndpoints = self::$doubleEndpointsLegacy;
                break;
            default:
                throw new \InvalidArgumentException(sprintf('The value of api_type should be one of [%s], got %s.', implode(', ', ApiType::casesValue()), json_encode($config['api_type'], \JSON_THROW_ON_ERROR)));
        }

        return isset($config['type'])
            && (\in_array($config['type'], $endpoints) || \in_array($config['type'], $doubleEndpoints))
            && isset($config['method'])
            && 'all' === $config['method'];
    }

    private function compileFilters(array ...$filters): Node\Expr
    {
        $builder = new Sylius\Builder\Search();
        foreach ($filters as $filter) {
            $builder->addFilter(
                field: compileValue($this->interpreter, $filter['field']),
                operator: compileValue($this->interpreter, $filter['operator']),
                value: compileValue($this->interpreter, $filter['value']),
                scope: \array_key_exists('scope', $filter) ? compileValue($this->interpreter, $filter['scope']) : null,
                locale: \array_key_exists('locale', $filter) ? compileValue($this->interpreter, $filter['locale']) : null
            );
        }

        return $builder->getNode();
    }

    public function getBuilder(array $config): Builder
    {
        $builder = (new Sylius\Builder\Capacity\All())
            ->withEndpoint(new Node\Identifier(sprintf('get%sApi', ucfirst((string) $config['type']))))
        ;

        if (isset($config['search']) && \is_array($config['search'])) {
            $builder->withSearch($this->compileFilters(...$config['search']));
        }

        return $builder;
    }
}
