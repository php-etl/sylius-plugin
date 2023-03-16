<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Configuration;

use Kiboko\Plugin\Sylius\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config;

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
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function validConfigWithPasswordAuthentication(): void
    {
        $client = new Configuration\Client();

        $this->assertSame(
            [
                'context' => [],
                'api_url' => 'http://api.example.com',
                'client_id' => 'LOREMIPSUM',
                'secret' => 'SECRET',
                'username' => 'JOHNDOE',
                'password' => 'PASSWORD',
            ],
            $this->processor->processConfiguration(
                $client,
                [
                    [
                        'context' => [],
                        'api_url' => 'http://api.example.com',
                        'client_id' => 'LOREMIPSUM',
                        'secret' => 'SECRET',
                        'username' => 'JOHNDOE',
                        'password' => 'PASSWORD',
                    ],
                ]
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function validConfigWithTokenAuthentication(): void
    {
        $client = new Configuration\Client();

        $this->assertSame(
            [
                'context' => [],
                'api_url' => 'http://api.example.com',
                'client_id' => 'LOREMIPSUM',
                'secret' => 'SECRET',
                'token' => 'TOKEN',
                'refresh_token' => 'REFRESH',
            ],
            $this->processor->processConfiguration(
                $client,
                [
                    [
                        'context' => [],
                        'api_url' => 'http://api.example.com',
                        'client_id' => 'LOREMIPSUM',
                        'secret' => 'SECRET',
                        'token' => 'TOKEN',
                        'refresh_token' => 'REFRESH',
                    ],
                ]
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function missingAuthenticationMethod(): void
    {
        $client = new Configuration\Client();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'You must choose between "username" and "token" as authentication method for Sylius API, both are mutually exclusive.',
        );

        $this->processor->processConfiguration(
            $client,
            [
                [
                    'context' => [],
                    'api_url' => 'http://api.example.com',
                    'client_id' => 'LOREMIPSUM',
                    'secret' => 'SECRET',
                ],
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function bothAuthenticationMethod(): void
    {
        $client = new Configuration\Client();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'You must choose between "username" and "token" as authentication method for Sylius API, both are mutually exclusive.',
        );

        $this->processor->processConfiguration(
            $client,
            [
                [
                    'context' => [],
                    'api_url' => 'http://api.example.com',
                    'client_id' => 'LOREMIPSUM',
                    'secret' => 'SECRET',
                    'username' => 'JOHNDOE',
                    'password' => 'PASSWORD',
                    'token' => 'TOKEN',
                    'refresh_token' => 'REFRESH',
                ],
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function missingPasswordInAuthenticationMethod(): void
    {
        $client = new Configuration\Client();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'The configuration option "password" should be defined if you use the username authentication method for Sylius API.',
        );

        $this->processor->processConfiguration(
            $client,
            [
                [
                    'context' => [],
                    'api_url' => 'http://api.example.com',
                    'client_id' => 'LOREMIPSUM',
                    'secret' => 'SECRET',
                    'username' => 'JOHNDOE',
                ],
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function missingRefreshTokenInAuthenticationMethod(): void
    {
        $client = new Configuration\Client();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'The configuration option "refreshToken" should be defined if you use the token authentication method for Sylius API.',
        );

        $this->processor->processConfiguration(
            $client,
            [
                [
                    'context' => [],
                    'api_url' => 'http://api.example.com',
                    'client_id' => 'LOREMIPSUM',
                    'secret' => 'SECRET',
                    'token' => 'TOKEN',
                ],
            ]
        );
    }
}
