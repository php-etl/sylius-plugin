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
    }

    public static function wrongApiType(): \Generator
    {
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'get',
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
                'method' => 'get',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'get',
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
                'type' => 'products',
                'api_type' => 'legacy',
            ],
        ];
        yield [
            'config' => [
                'api_type' => 'legacy',
                'method' => 'get',
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
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('The value of api_type should be one of [admin, shop, legacy], got null.');

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
}
