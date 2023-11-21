<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\Sylius\Factory\Extractor;
use Kiboko\Plugin\Sylius\Factory\Loader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ExtractorTest extends TestCase
{
    public static function validDataProvider(): \Generator
    {
        yield [
            [
                'type' => 'products',
                'method' => 'all',
                'api_type' => 'legacy',
            ],
        ];
        yield [
            [
                'type' => 'products',
                'method' => 'listPerPage',
                'api_type' => 'legacy',
            ],
        ];
        yield [
            [
                'type' => 'product',
                'method' => 'all',
                'api_type' => 'admin',
            ],
        ];
        yield [
            [
                'type' => 'product',
                'method' => 'listPerPage',
                'api_type' => 'admin',
            ],
        ];
        yield [
            [
                'type' => 'product',
                'method' => 'all',
                'api_type' => 'shop',
            ],
        ];
        yield [
            [
                'type' => 'product',
                'method' => 'listPerPage',
                'api_type' => 'shop',
            ],
        ];
    }

    public static function wrongApiType(): \Generator
    {
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'all',
                'api_type' => 'wrong',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'api_type' => 'wrong',
            ],
        ];
        yield [
            'config' => [
                'method' => 'all',
                'api_type' => 'wrong',
            ],
        ];
        yield [
            'config' => [
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
                'method' => 'all',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'all',
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
                'api_type' => 'admin',
            ],
        ];
        yield [
            'config' => [
                'api_type' => 'shop',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'api_type' => 'legacy',
            ],
        ];
        yield [
            'config' => [
                'api_type' => 'legacy',
                'method' => 'all',
            ],
        ];
        yield [
            'config' => [
                'type' => 'product',
                'api_type' => 'admin',
            ],
        ];
        yield [
            'config' => [
                'api_type' => 'admin',
                'method' => 'all',
            ],
        ];
        yield [
            'config' => [
                'type' => 'product',
                'api_type' => 'shop',
            ],
        ];
        yield [
            'config' => [
                'api_type' => 'shop',
                'method' => 'all',
            ],
        ];
        yield [
            'config' => [
                'type' => 'wrong',
                'method' => 'all',
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
        yield [
            'config' => [
                'type' => 'wrong',
                'method' => 'all',
                'api_type' => 'admin',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'wrong',
                'api_type' => 'admin',
            ],
        ];
        yield [
            'config' => [
                'type' => 'wrong',
                'method' => 'all',
                'api_type' => 'shop',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'wrong',
                'api_type' => 'shop',
            ],
        ];
    }

    public static function wrongConfigs(): \Generator
    {
        yield [
            'config' => [
                'azerty' => 'tata',
                'api_type' => 'legacy',
            ],
        ];
        yield [
            'config' => [
                'azerty' => 'tata',
                'meyuiop' => 'toto',
                'api_type' => 'admin',
            ],
        ];
        yield [
            'config' => [
                'azerty' => 'tata',
                'meyuiop' => 'toto',
                'qsdfgh' => 'tutu',
                'api_type' => 'shop',
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validDataProvider')]
    public function testValidateConfiguration(array $config): void
    {
        $client = new Extractor(new ExpressionLanguage());
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

        $client = new Extractor(new ExpressionLanguage());
        $this->assertFalse($client->validate($config));
        $client->compile($config);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('wrongConfigs')]
    public function testWrongConfigs(array $config): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Your Sylius API configuration is using some unsupported capacity, check your "type" and "method" properties to a suitable set.');

        $client = new Extractor(new ExpressionLanguage());
        $this->assertFalse($client->validate($config));
        $client->compile($config);
    }
}
