<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Validator;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class LoaderConfigurationValidator
{
    final public const ADMIN_VALID_TYPES = [
        'address' => [
            'create',
            'delete',
            'update',
        ],
        'administrator' => [
            'create',
            'delete',
            'update',
        ],
        'avatarImage' => [
            'create',
            'delete',
        ],
        'catalogPromotion' => [
            'create',
            'update',
            'delete',
        ],
        'channel' => [
            'create',
            'update',
            'delete',
        ],
        'country' => [
            'create',
            'update',
        ],
        'province' => [
            'update',
        ],
        'currency' => [
            'create',
        ],
        'customerGroup' => [
            'create',
            'delete',
            'update',
        ],
        'customer' => [
            'create',
            'delete',
            'update',
        ],
        'exchangeRate' => [
            'create',
            'delete',
            'update',
        ],
        'locale' => [
            'create',
            'delete',
        ],
        'paymentMethod' => [
            'create',
            'delete',
            'update',
        ],
        'productAssociationType' => [
            'create',
            'delete',
            'update',
        ],
        'productAssociation' => [
            'create',
            'delete',
            'update',
        ],
        'productAttribute' => [
            'create',
            'delete',
            'update',
        ],
        'productImage' => [
            'delete',
            'update',
        ],
        'productOption' => [
            'create',
            'update',
            'delete',
        ],
        'productReview' => [
            'create',
            'update',
            'delete',
        ],
        'productTaxon' => [
            'create',
            'update',
            'delete',
        ],
        'productVariant' => [
            'create',
            'update',
            'delete',
        ],
        'product' => [
            'create',
            'delete',
            'update',
        ],
        'promotionCoupon' => [
            'create',
            'update',
            'delete',
        ],
        'promotion' => [
            'create',
            'update',
            'delete',
        ],
        'shippingCategory' => [
            'create',
            'delete',
            'update',
        ],
        'shippingMethod' => [
            'create',
            'delete',
            'update',
        ],
        'taxCategory' => [
            'create',
            'delete',
            'update',
        ],
        'taxRate' => [
            'create',
            'delete',
            'update',
        ],
        'taxonImage' => [
            'delete',
            'update',
        ],
        'taxon' => [
            'create',
            'update',
            'delete',
        ],
        'zone' => [
            'create',
            'delete',
            'update',
        ],
    ];

    final public const SHOP_VALID_TYPES = [
        'address' => [
            'create',
            'delete',
            'update',
        ],
        'customer' => [
            'create',
            'update',
        ],
        'order' => [
            'create',
            'update',
            'delete',
        ],
        'orderItem' => [
            'create',
            'delete',
        ],
        'productReview' => [
            'create',
        ],
    ];


    public static function validate(string $type, string $method, string $version = 'admin'): void
    {
        $validTypes = $version === 'admin' ? self::ADMIN_VALID_TYPES : self::SHOP_VALID_TYPES;

        if ($validTypes === null) {
            throw new InvalidConfigurationException(sprintf('Unknown version "%s".', $version));
        }

        if (!array_key_exists($type, $validTypes)) {
            throw new InvalidConfigurationException(sprintf(
                'Invalid loader type "%s" for version "%s". Valid types are: %s.',
                $type,
                $version,
                implode(', ', array_keys($validTypes))
            ));
        }

        if (!in_array($method, $validTypes[$type], true)) {
            throw new InvalidConfigurationException(sprintf(
                'Invalid method "%s" for extractor type "%s" and version "%s". Valid methods are: %s.',
                $method,
                $type,
                $version,
                implode(', ', $validTypes[$type])
            ));
        }
    }
}
