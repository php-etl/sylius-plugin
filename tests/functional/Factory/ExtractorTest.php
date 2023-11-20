<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\Sylius\Factory\Extractor;
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

    public static function wrongConfigs(): \Generator
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
                'type' => 'products',
                'api_type' => 'legacy',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
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

    #[\PHPUnit\Framework\Attributes\DataProvider('wrongConfigs')]
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
