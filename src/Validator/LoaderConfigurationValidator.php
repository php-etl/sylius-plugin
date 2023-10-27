<?php

namespace Kiboko\Plugin\Sylius\Validator;

class LoaderConfigurationValidator implements ConfigurationValidatorInterface
{
    private static array $endpointsLegacy = [
        // Core Endpoints
        'channels' => [
            'create',
            'delete',
        ],
        'countries' => [
            'create',
            'delete',
        ],
        'carts' => [
            'create',
            'delete',
        ],
        'currencies' => [
            'create',
            'delete',
        ],
        'customers' => [
            'create',
            'delete',
        ],
        'exchangeRates' => [
            'create',
            'delete',
        ],
        'locales' => [
            'create',
            'delete',
        ],
        'orders' => [
            'create',
            'delete',
        ],
        'payments' => [
            'create',
            'delete',
        ],
        'paymentMethods' => [
            'create',
            'delete',
        ],
        'products' => [
            'create',
            'upsert',
            'delete',
        ],
        'productAttributes' => [
            'create',
            'delete',
        ],
        'productAssociationTypes' => [
            'create',
            'delete',
        ],
        'productOptions' => [
            'create',
            'delete',
        ],
        'promotions' => [
            'create',
            'delete',
        ],
        'shipments' => [
            'create',
            'delete',
        ],
        'shippingCategories' => [
            'create',
            'delete',
        ],
        'taxCategories' => [
            'create',
            'delete',
        ],
        'taxRates' => [
            'create',
            'delete',
        ],
        'taxons' => [
            'create',
            'delete',
        ],
        'users' => [
            'create',
            'delete',
        ],
        'zones' => [
            'create',
            'delete',
        ],
    ];

    private static array $endpointsAdmin = [
        // Core Endpoints
        'administrator' => [
            'create',
            'delete',
            'upsert',
        ],
        'avatarImage' => [
            'create',
            'delete',
        ],
        'catalogPromotion' => [
            'create',
            'upsert',
        ],
        'channel' => [
            'create',
            'delete',
        ],
        'country' => [
            'create',
            'delete',
        ],
        'currency' => [
            'create',
            'delete',
        ],
        'customerGroup' => [
            'create',
            'delete',
            'upsert',
        ],
        'exchangeRate' => [
            'create',
            'delete',
            'upsert',
        ],
        'locale' => [
            'create',
        ],
        'order' => [
            'cancel',
        ],
        'payment' => [
            'complete',
        ],
        'product' => [
            'create',
            'delete',
            'upsert',
        ],
        'productAssociationType' => [
            'create',
            'delete',
            'upsert',
        ],
        'productOption' => [
            'create',
            'delete',
        ],
        'productReview' => [
            'upsert',
            'delete',
            'accept',
            'reject',
        ],
        'productVariant' => [
            'create',
            'upsert',
        ],
        'promotion' => [
            'create',
            'delete',
        ],
        'province' => [
            'upsert',
        ],
        'resetPasswordRequest' => [
            'create',
            'acknowledge',
        ],
        'shipment' => [
            'ship',
        ],
        'shippingCategory' => [
            'create',
            'delete',
            'upsert',
        ],
        'shippingMethod' => [
            'create',
            'delete',
            'upsert',
            'archive',
            'restore',
        ],
        'taxCategory' => [
            'create',
            'delete',
            'upsert',
        ],
        'taxon' => [
            'create',
            'upsert',
        ],
        'verifyCustomerAccount' => [
            'create',
            'acknowledge',
        ],
        'zone' => [
            'create',
            'delete',
            'upsert',
        ],
    ];

    private static array $endpointsShop = [
        // Core Endpoints
        'address' => [
            'create',
            'delete',
            'upsert',
        ],
        'customer' => [
            'create',
            'upsert',
            'changePassword',
        ],
        'order' => [
            'create',
            'upsert',
            'choosePayment',
            'chooseShipment',
            'complete',
        ],
        'orderItem' => [
            'create',
            'delete',
            'changeQuantity',
        ],
        'productReview' => [
            'create',
        ],
        'resetPasswordRequest' => [
            'create',
            'verify',
        ],
        'verifyCustomerAccount' => [
            'create',
            'verify',
        ],
    ];
    public static function validate(array $item): array
    {
        $endpoints = match ($item['api_type']) {
            'admin' => self::$endpointsAdmin,
            'shop' => self::$endpointsShop,
            'legacy' => self::$endpointsLegacy
        };
        if (!\in_array($item['type'], array_keys($endpoints))) {
            throw new \InvalidArgumentException(sprintf('the value should be one of [%s], got %s', implode(', ', array_keys($endpoints)), json_encode($item['type'], \JSON_THROW_ON_ERROR)));
        }
        if (!\in_array($item['method'], $endpoints[$item['type']])) {
            throw new \InvalidArgumentException(sprintf('the value should be one of [%s], got %s', implode(', ', $endpoints[$item['type']]), json_encode($item['method'], \JSON_THROW_ON_ERROR)));
        }
        return $item;
    }
}
