<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Validator;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ExtractorConfigurationValidator implements ConfigurationValidatorInterface
{
    private static array $endpointsLegacy = [
        // Core Endpoints
        'channels' => [
            'listPerPage',
            'all',
            'get',
        ],
        'countries' => [
            'listPerPage',
            'all',
            'get',
        ],
        'carts' => [
            'listPerPage',
            'all',
            'get',
        ],
        'currencies' => [
            'listPerPage',
            'all',
            'get',
        ],
        'customers' => [
            'listPerPage',
            'all',
            'get',
        ],
        'exchangeRates' => [
            'listPerPage',
            'all',
            'get',
        ],
        'locales' => [
            'listPerPage',
            'all',
            'get',
        ],
        'orders' => [
            'listPerPage',
            'all',
            'get',
        ],
        'payments' => [
            'listPerPage',
            'all',
            'get',
        ],
        'paymentMethods' => [
            'listPerPage',
            'all',
            'get',
        ],
        'products' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productAttributes' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productAssociationTypes' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productOptions' => [
            'listPerPage',
            'all',
            'get',
        ],
        'promotions' => [
            'listPerPage',
            'all',
            'get',
        ],
        'shipments' => [
            'listPerPage',
            'all',
            'get',
        ],
        'shippingCategories' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxCategories' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxRates' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxons' => [
            'listPerPage',
            'all',
            'get',
        ],
        'users' => [
            'listPerPage',
            'all',
            'get',
        ],
        'zones' => [
            'listPerPage',
            'all',
            'get',
        ],
    ];

    private static array $endpointsAdmin = [
        'address' => [
            'get',
        ],
        'adjustment' => [
            'listPerPage',
            'all',
            'get',
        ],
        'administrator' => [
            'listPerPage',
            'all',
            'get',
        ],
        'avatarImage' => [
            'get',
        ],
        'catalogPromotion' => [
            'listPerPage',
            'all',
            'get',
        ],
        'catalogPromotionTranslation' => [
            'get',
        ],
        'channel' => [
            'listPerPage',
            'all',
            'get',
        ],
        'country' => [
            'listPerPage',
            'all',
            'get',
        ],
        'currency' => [
            'listPerPage',
            'all',
            'get',
        ],
        'customer' => [
            'get',
        ],
        'customerGroup' => [
            'listPerPage',
            'all',
            'get',
        ],
        'exchangeRate' => [
            'listPerPage',
            'all',
            'get',
        ],
        'locale' => [
            'listPerPage',
            'all',
            'get',
        ],
        'order' => [
            'listPerPage',
            'all',
            'get',
            'listPaymentsPerPage',
            'allPayments',
            'listShipmentsPerPage',
            'allShipments',
        ],
        'orderItem' => [
            'get',
        ],
        'orderItemUnit' => [
            'get',
        ],
        'payment' => [
            'listPerPage',
            'all',
            'get',
        ],
        'paymentMethod' => [
            'get',
        ],
        'product' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productAssociationType' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productAssociationTypeTranslation' => [
            'get',
        ],
        'productImage' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productOption' => [
            'listPerPage',
            'all',
            'get',
            'listValuesPerPage',
            'allValues',
        ],
        'productOptionTranslation' => [
            'get',
        ],
        'productOptionValue' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productReview' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productTaxon' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productTranslation' => [
            'get',
        ],
        'productVariant' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productVariantTranslation' => [
            'get',
        ],
        'promotion' => [
            'listPerPage',
            'all',
            'get',
        ],
        'province' => [
            'listPerPage',
            'all',
            'get',
        ],
        'shipment' => [
            'listPerPage',
            'all',
            'get',
        ],
        'shippingCategory' => [
            'listPerPage',
            'all',
            'get',
        ],
        'shippingMethod' => [
            'listPerPage',
            'all',
            'get',
        ],
        'ShopBillingData' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxCategory' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxon' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxonTranslation' => [
            'listPerPage',
            'all',
            'get',
        ],
        'zone' => [
            'listPerPage',
            'all',
            'get',
        ],
        'zoneMember' => [
            'listPerPage',
            'all',
            'get',
        ],
    ];

    private static array $endpointsShop = [
        // Core Endpoints
        'address' => [
            'listPerPage',
            'all',
            'get',
        ],
        'adjustment' => [
            'listPerPage',
            'all',
            'get',
        ],
        'catalogPromotion' => [
            'get',
        ],
        'channel' => [
            'get',
        ],
        'country' => [
            'listPerPage',
            'all',
            'get',
        ],
        'currency' => [
            'listPerPage',
            'all',
            'get',
        ],
        'customer' => [
            'get',
        ],
        'locale' => [
            'listPerPage',
            'all',
            'get',
        ],
        'order' => [
            'listPerPage',
            'all',
            'get',
            'listPaymentMethodsPerPage',
            'allPaymentMethods',
            'listShipmentMethodsPerPage',
            'allShipmentMethods',
            'listAdjustmentsPerPage',
            'allAdjustments',
            'listItemsPerPage',
            'allItems',
        ],
        'orderItem' => [
            'listPerPage',
            'all',
            'get',
            'listAdjustmentsPerPage',
            'allAdjustments',
        ],
        'orderItemUnit' => [
            'get',
        ],
        'payment' => [
            'listPerPage',
            'all',
            'get',
        ],
        'paymentMethod' => [
            'listPerPage',
            'all',
            'get',
        ],
        'product' => [
            'listPerPage',
            'all',
            'get',
            'getBySlug',
        ],
        'productImage' => [
            'get',
        ],
        'productOption' => [
            'get',
        ],
        'productOptionValue' => [
            'get',
        ],
        'productReview' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productTaxon' => [
            'get',
        ],
        'productTranslation' => [
            'get',
        ],
        'productVariant' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productVariantTranslation' => [
            'get',
        ],
        'shipment' => [
            'listPerPage',
            'all',
            'get',
        ],
        'shippingMethod' => [
            'listPerPage',
            'all',
            'get',
        ],
        'shippingMethodTranslation' => [
            'get',
        ],
        'taxon' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxonTranslation' => [
            'get',
        ],
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

    public static function getEndpointsApiType(): array
    {
        return match (self::$currentApiType) {
            ApiType::ADMIN->value => self::$endpointsAdmin,
            ApiType::SHOP->value => self::$endpointsShop,
            ApiType::LEGACY->value => self::$endpointsLegacy,
            default => throw new \UnhandledMatchError(self::$currentApiType)
        };
    }

    public static function getDoubleEndpointsApiType(): array
    {
        return match (self::$currentApiType) {
            ApiType::ADMIN->value => self::$doubleEndpointsAdmin,
            ApiType::SHOP->value => self::$doubleEndpointsShop,
            ApiType::LEGACY->value => self::$doubleEndpointsLegacy,
            default => throw new \UnhandledMatchError(self::$currentApiType)
        };
    }
    public static string $currentApiType;
    public static string $currentType;

    public static function validate(array $item): array
    {
        if (\in_array($item['type'], self::getDoubleEndpointsApiType()) && !\array_key_exists('code', $item)) {
            throw new InvalidConfigurationException('The code parameters is required and cannot be empty because you choose a type: '.$item['type']);
        }

        return $item;
    }

    public static function validateApiType(string $apiType)
    {
        self::$currentApiType = $apiType;
        if (!\in_array($apiType, ApiType::casesValue())) {
            throw new \InvalidArgumentException(sprintf('the value should be one of [%s], got %s.', implode(', ', ApiType::casesValue()), json_encode($apiType, \JSON_THROW_ON_ERROR)));
        }

        return $apiType;
    }

    public static function validateType(string $type)
    {
        self::$currentType = $type;
        $endpoints = self::getEndpointsApiType();
        $doubleEndpoints = self::getDoubleEndpointsApiType();
        if (!\in_array($type, array_merge(array_keys($endpoints), $doubleEndpoints))) {
            throw new \InvalidArgumentException(sprintf('the value should be one of [%s], got %s.', implode(', ', array_merge(array_keys($endpoints), $doubleEndpoints)), json_encode($type, \JSON_THROW_ON_ERROR)));
        }

        return $type;
    }

    public static function validateMethod(string $method)
    {
        $endpoints = self::getEndpointsApiType();
        $doubleEndpoints = self::getDoubleEndpointsApiType();
        if (
            \array_key_exists(self::$currentType, $endpoints)
            && !\in_array($method, $endpoints[self::$currentType])
            && !\in_array(self::$currentType, $doubleEndpoints)
        ) {
            throw new \InvalidArgumentException(sprintf('The value should be one of [%s], got %s.', implode(', ', $endpoints[self::$currentType]), json_encode($method, \JSON_THROW_ON_ERROR)));
        }

        return $method;
    }

    public static function validateCode(string $code)
    {
        $doubleEndpoints = self::getDoubleEndpointsApiType();
        if (\in_array(self::$currentType, $doubleEndpoints)) {
            throw new \InvalidArgumentException(sprintf('The %s type should have a "code" field set.', self::$currentType), json_encode($code, \JSON_THROW_ON_ERROR));
        }

        return $code;
    }
}
