<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Builder;

use Diglin\Sylius\ApiClient\SyliusAdminClientBuilder;
use Diglin\Sylius\ApiClient\SyliusShopClientBuilder;
use Kiboko\Plugin\Sylius\MissingAuthenticationMethodException;
use Kiboko\Plugin\Sylius\Validator\ApiType;
use PhpParser\Builder;
use PhpParser\Node;

final class Client implements Builder
{

    private ?Node\Expr $clientId = null;
    private ?Node\Expr $secret = null;
    private ?Node\Expr $username = null;
    private ?Node\Expr $password = null;
    private ?Node\Expr $token = null;
    private ?Node\Expr $refreshToken = null;
    private ?Node\Expr $httpClient = null;
    private ?Node\Expr $httpRequestFactory = null;
    private ?Node\Expr $httpStreamFactory = null;
    private ?Node\Expr $fileSystem = null;
    private string $apiType;

    public const API_ADMIN_KEY = 'admin';
    public const API_SHOP_KEY = 'shop';
    public const API_LEGACY_KEY = 'legacy';

    public function __construct(private readonly Node\Expr $baseUrl) {}

    public function withSecret(Node\Expr $clientId, Node\Expr $secret): self
    {
        $this->clientId = $clientId;
        $this->secret = $secret;

        return $this;
    }

    public function withToken(Node\Expr $token, Node\Expr $refreshToken): self
    {
        $this->token = $token;
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function withApiType(string $apiType): self
    {
        $this->apiType = $apiType;

        return $this;
    }

    public function withPassword(Node\Expr $username, Node\Expr $password): self
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    public function withHttpClient(Node\Expr $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function withHttpRequestFactory(Node\Expr $httpRequestFactory): self
    {
        $this->httpRequestFactory = $httpRequestFactory;

        return $this;
    }

    public function withHttpStreamFactory(Node\Expr $httpStreamFactory): self
    {
        $this->httpStreamFactory = $httpStreamFactory;

        return $this;
    }

    public function withFileSystem(Node\Expr $fileSystem): self
    {
        $this->fileSystem = $fileSystem;

        return $this;
    }

    public function getNode(): Node\Expr\MethodCall
    {
        $instance = $this->getClientBuilderNode();

        if (null !== $this->httpClient) {
            $instance = new Node\Expr\MethodCall(
                $instance,
                'setHttpClient',
                [
                    new Node\Arg($this->httpClient),
                ],
            );
        }

        if (null !== $this->httpRequestFactory) {
            $instance = new Node\Expr\MethodCall(
                $instance,
                'setRequestFactory',
                [
                    new Node\Arg($this->httpRequestFactory),
                ],
            );
        }

        if (null !== $this->httpStreamFactory) {
            $instance = new Node\Expr\MethodCall(
                $instance,
                'setStreamFactory',
                [
                    new Node\Arg($this->httpStreamFactory),
                ],
            );
        }

        if (null !== $this->fileSystem) {
            $instance = new Node\Expr\MethodCall(
                $instance,
                'setFileSystem',
                [
                    new Node\Arg($this->fileSystem),
                ],
            );
        }

        return new Node\Expr\MethodCall(
            $instance,
            $this->getFactoryMethod(),
            $this->getFactoryArguments(),
        );
    }

    private function getClientBuilderNode(): Node\Expr\MethodCall
    {
        $className = match ($this->apiType) {
            ApiType::ADMIN->value => SyliusAdminClientBuilder::class,
            ApiType::SHOP->value => SyliusShopClientBuilder::class,
            ApiType::LEGACY->value => 'Diglin\\Sylius\\ApiClient\\SyliusClientBuilder',
            default => throw new \UnhandledMatchError($this->apiType)
        };

        return new Node\Expr\MethodCall(
            var: new Node\Expr\New_(
                new Node\Name\FullyQualified($className),
            ),
            name: new Node\Identifier('setBaseUri'),
            args: [
                new Node\Arg($this->baseUrl),
            ],
        );
    }

    private function getFactoryMethod(): string
    {
        if (null !== $this->password) {
            return 'buildAuthenticatedByPassword';
        }

        if (null !== $this->refreshToken) {
            return 'buildAuthenticatedByToken';
        }

        throw new MissingAuthenticationMethodException('Please check your client builder, you should either call withToken() or withPassword() methods.');
    }

    private function getFactoryArguments(): array
    {
        if (null !== $this->password) {
            if ($this->apiType === ApiType::LEGACY->value) {
                return [
                    $this->clientId,
                    $this->secret,
                    $this->username,
                    $this->password,
                ];
            }

            return [
                $this->username,
                $this->password,
            ];
        }

        if (null !== $this->refreshToken) {
            if ($this->apiType === ApiType::LEGACY->value) {
                return [
                    $this->clientId,
                    $this->secret,
                    $this->token,
                    $this->refreshToken,
                ];
            }

            return [
                $this->token,
            ];
        }

        throw new MissingAuthenticationMethodException('Please check your client builder, you should either call withToken() or withPassword() methods.');
    }
}
