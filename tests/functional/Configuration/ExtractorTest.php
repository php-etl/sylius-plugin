<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Configuration;

use Kiboko\Plugin\Sylius\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config;

final class ExtractorTest extends TestCase
{
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    public function validDataProvider(): iterable
    {
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'all',
                'search' => [],
            ],
            'expected' => [
                'type' => 'products',
                'method' => 'all',
                'search' => [],
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'listPerPage',
                'search' => [],
            ],
            'expected' => [
                'type' => 'products',
                'method' => 'listPerPage',
                'search' => [],
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'get',
                'search' => [],
            ],
            'expected' => [
                'type' => 'products',
                'method' => 'get',
                'search' => [],
            ],
        ];
    }

    /** @dataProvider validDataProvider */
    public function testValidConfig(array $config, array $expected)
    {
        $client = new Configuration\Extractor();

        $this->assertSame($expected, $this->processor->processConfiguration($client, [$config]));
    }

    public function testInvalidConfig()
    {
        $client = new Configuration\Extractor();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'Invalid configuration for path "extractor": The value should be one of [listPerPage, all, get], got "invalidValue".',
        );

        $this->processor->processConfiguration($client, [
            [
                'type' => 'products',
                'method' => 'invalidValue'
            ]
        ]);
    }
}
