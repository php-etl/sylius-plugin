<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius;

enum ApiType: string
{
    case ADMIN = 'admin';
    case SHOP = 'shop';
}
