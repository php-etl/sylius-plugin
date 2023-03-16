<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\Sylius\Factory\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversNothing]
/**
 * @internal
 *
 * @coversNothing
 */
final class ClientTest extends TestCase
{
    public static function validDataProvider(): \Generator
    {
        yield [
            [
                'api_url' => '123',
                'client_id' => '123',
                'secret' => '123',
                'username' => '123',
                'password' => '123',
            ],
        ];

        yield [
            [
                'api_url' => '123',
                'client_id' => '123',
                'secret' => '123',
                'username' => '123',
                'password' => '123',
                'context' => [
                    'http_client' => 'truc',
                ],
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validDataProvider')]
    /**
     * @test
     */
    public function validateConfiguration(array $config): void
    {
        $client = new Client(new ExpressionLanguage());
        $this->assertTrue($client->validate([$config]));
        $client->compile($config);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function missingCapacity(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $client = new Client(new ExpressionLanguage());
        $this->assertFalse($client->validate([]));
        $client->normalize([]);
    }
}
