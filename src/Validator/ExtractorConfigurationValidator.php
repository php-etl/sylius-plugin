<?php

namespace Kiboko\Plugin\Sylius\Validator;

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
            'allShipments'
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
    public static function validate(array $item): array
    {
        switch($item['api_type']) {
            case ApiType::ADMIN->value:
                $endpoints = self::$endpointsAdmin;
                $doubleEndpoints = self::$doubleEndpointsAdmin;
                break;
            case ApiType::SHOP->value:
                $endpoints = self::$endpointsShop;
                $doubleEndpoints = self::$doubleEndpointsShop;
                break;
            case ApiType::LEGACY->value:
                $endpoints = self::$endpointsLegacy;
                $doubleEndpoints = self::$doubleEndpointsLegacy;
                break;
            default:
                $endpoints = [];
                $doubleEndpoints = [];
                break;
        }
        if (!\in_array($item['type'], array_merge(array_keys($endpoints), $doubleEndpoints))) {
            throw new \InvalidArgumentException(sprintf('the value should be one of [%s], got %s', implode(', ', array_merge(array_keys($endpoints), $doubleEndpoints)), json_encode($item['type'], \JSON_THROW_ON_ERROR)));
        }
        if (
            \array_key_exists($item['type'], $endpoints)
            && !\in_array($item['method'], $endpoints[$item['type']])
            && !\in_array($item['type'], $doubleEndpoints)
        ) {
            throw new \InvalidArgumentException(sprintf('The value should be one of [%s], got %s.', implode(', ', $endpoints[$item['type']]), json_encode($item['method'], \JSON_THROW_ON_ERROR)));
        }
        if (\in_array($item['type'], $doubleEndpoints) && !\array_key_exists('code', $item)) {
            throw new \InvalidArgumentException(sprintf('The %s type should have a "code" field set.', $item['type']));
        }

        return $item;
    }
}
