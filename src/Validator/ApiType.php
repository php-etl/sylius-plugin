<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Validator;

enum ApiType: string
{
    case ADMIN = 'admin';
    case SHOP = 'shop';
    case LEGACY = 'legacy';

    public static function casesValue(): array
    {
        $apiTypeCases = [];
        foreach (ApiType::cases() as $cases)
        {
            $apiTypeCases[] = $cases->value;
        }
        return $apiTypeCases;
    }
}
