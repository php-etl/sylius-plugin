<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Validator;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ExtractorConfigurationValidator
{
    final public const ADMIN_VALID_TYPES = [
        'address' => [
            'get',
        ],
        'adjustment' => [
            'get',
        ],
        'administrator' => [
            'all',
            'get',
        ],
        'catalogPromotionTranslation' => [
            'get',
        ],
        'catalogPromotion' => [
            'all',
            'get',
        ],
        'channel' => [
            'all',
            'get',
        ],
        'shopBillingData' => [
            'get',
        ],
        'country' => [
            'all',
            'get',
        ],
        'province' => [
            'all',
            'get',
        ],
        'currency' => [
            'all',
            'get',
        ],
        'customerGroups' => [
            'all',
            'get',
        ],
        'customer' => [
            'all',
            'get',
        ],
        'exchangeRate' => [
            'all',
            'get',
        ],
        'gatewayConfiguration' => [
            'get',
        ],
        'locale' => [
            'all',
            'get',
        ],
        'orderItemUnit' => [
            'get',
        ],
        'orderItem' => [
            'get',
        ],
        'order' => [
            'all',
            'get',
        ],
        'paymentMethod' => [
            'all',
            'get',
        ],
        'payment' => [
            'all',
            'get',
        ],
        'shipment' => [
            'all',
            'get',
        ],
        'productAssociationType' => [
            'all',
            'get',
        ],
        'productAttribute' => [
            'all',
            'get',
        ],
        'productOption' => [
            'all',
            'get',
        ],
        'productReview' => [
            'all',
            'get',
        ],
        'productTaxon' => [
            'all',
            'get',
        ],
        'productTranslation' => [
            'get',
        ],
        'productVariantTranslation' => [
            'get',
        ],
        'productVariant' => [
            'all',
            'get',
        ],
        'product' => [
            'all',
            'get',
        ],
        'promotionCoupon' => [
            'all',
            'get',
        ],
        'promotionTranslation' => [
            'get',
        ],
        'promotion' => [
            'all',
            'get',
        ],
        'shippingCategory' => [
            'all',
            'get',
        ],
        'shippingMethodTranslation' => [
            'get',
        ],
        'shippingMethod' => [
            'all',
            'get',
        ],
        'taxCategory' => [
            'all',
            'get',
        ],
        'taxRate' => [
            'all',
            'get',
        ],
        'taxonImage' => [
            'all',
            'get',
        ],
        'taxon' => [
            'all',
            'get',
        ],
        'zoneMember' => [
            'get',
        ],
        'zone' => [
            'all',
            'get',
        ],
    ];

    final public const SHOP_VALID_TYPES = [
        'address' => [
            'all',
            'get',
        ],
        'adjustment' => [
            'get',
        ],
        'catalogPromotion' => [
            'get',
        ],
        'channel' => [
            'all',
            'get',
        ],
        'country' => [
            'all',
            'get',
        ],
        'province' => [
            'get',
        ],
        'currency' => [
            'all',
            'get',
        ],
        'customer' => [
            'get',
        ],
        'exchangeRate' => [
            'all',
            'get',
        ],
        'locale' => [
            'all',
            'get',
        ],
        'orderItemUnit' => [
            'get',
        ],
        'orderItem' => [
            'get',
        ],
        'order' => [
            'all',
            'get',
        ],
        'paymentMethod' => [
            'all',
            'get',
        ],
        'payment' => [
            'get',
        ],
        'shipment' => [
            'get',
        ],
        'productAssociationType' => [
            'get',
        ],
        'productAttribute' => [
            'get',
        ],
        'productOption' => [
            'get',
        ],
        'productReview' => [
            'all',
            'get',
        ],
        'productTaxon' => [
            'get',
        ],
        'productVariant' => [
            'all',
            'get',
        ],
        'product' => [
            'all',
            'get',
        ],
        'shippingMethod' => [
            'all',
            'get',
        ],
        'taxonImage' => [
            'get',
        ],
        'taxon' => [
            'all',
            'get',
        ],
    ];

    public static function validate(string $type, string $method, string $version = 'admin'): void
    {
        $validTypes = 'admin' === $version ? self::ADMIN_VALID_TYPES : self::SHOP_VALID_TYPES;

        if (null === $validTypes) {
            throw new InvalidConfigurationException(sprintf('Unknown version "%s".', $version));
        }

        if (!\array_key_exists($type, $validTypes)) {
            throw new InvalidConfigurationException(sprintf('Invalid extractor type "%s" for version "%s". Valid types are: %s.', $type, $version, implode(', ', array_keys($validTypes))));
        }

        if (!\in_array($method, $validTypes[$type], true)) {
            throw new InvalidConfigurationException(sprintf('Invalid method "%s" for extractor type "%s" and version "%s". Valid methods are: %s.', $method, $type, $version, implode(', ', $validTypes[$type])));
        }
    }
}
