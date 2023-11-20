<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\Sylius\Factory\Loader;
use PHPUnit\Framework\TestCase;

final class LoaderTest extends TestCase
{
    public static function validDataProvider(): \Generator
    {
        yield [
            [
                'type' => 'products',
                'method' => 'create',
                'api_type' => 'legacy',
            ],
        ];

        yield [
            [
                'type' => 'products',
                'method' => 'upsert',
                'api_type' => 'legacy',
            ],
        ];
    }

    public static function wrongApiType(): \Generator
    {
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'upsert',
                'api_type' => 'wrong',
            ],
        ];
    }

    public static function missingApiType(): \Generator
    {
        yield [
            'config' => [
            ],
        ];
        yield [
            'config' => [
                'wrong',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
            ],
        ];
        yield [
            'config' => [
                'method' => 'upsert',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'upsert',
            ],
        ];
    }

    public static function missingCapacityConfigs(): \Generator
    {
        yield [
            'config' => [
                'api_type' => 'legacy',
            ],
        ];
        yield [
            'config' => [
                'api_type' => 'legacy',
                'type' => 'products',
            ],
        ];
        yield [
            'config' => [
                'method' => 'upsert',
                'api_type' => 'legacy',
            ],
        ];
        yield [
            'config' => [
                'type' => 'wrong',
                'method' => 'upsert',
                'api_type' => 'legacy',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'wrong',
                'api_type' => 'legacy',
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validDataProvider')]
    public function testValidateConfiguration(array $config): void
    {
        $client = new Loader();
        $this->assertTrue($client->validate([$config]));
        $client->compile($config);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('wrongApiType')]
    public function testWrongApiType(array $config)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('The value of api_type should be one of [admin, shop, legacy], got "wrong".');

        $client = new Loader();
        $this->assertFalse($client->validate($config));
        $client->compile($config);
    }


    #[\PHPUnit\Framework\Attributes\DataProvider('missingApiType')]
    public function testMissingApiType(array $config)
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Your Sylius API configuration is using some unsupported capacity, check your "api_type" properties to a suitable set.');

        $client = new Loader();
        $this->assertFalse($client->validate($config));
        $client->compile($config);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('missingCapacityConfigs')]
    public function testMissingCapacity(array $config): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Your Sylius API configuration is using some unsupported capacity, check your "type" and "method" properties to a suitable set.');

        $client = new Loader();
        $this->assertFalse($client->validate($config));
        $client->compile($config);
    }
}
