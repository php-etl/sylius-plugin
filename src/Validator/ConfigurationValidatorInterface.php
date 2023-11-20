<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Validator;

interface ConfigurationValidatorInterface
{
    public static function validate(array $item): array;
}
