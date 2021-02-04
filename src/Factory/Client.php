<?php declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Factory;

use Kiboko\Plugin\Sylius;
use Kiboko\Contract\Configurator;
use PhpParser\Node;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;

final class Client implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    public function __construct()
    {
        $this->processor = new Processor();
        $this->configuration = new Sylius\Configuration\Client();
    }

    public function configuration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @throws Configurator\ConfigurationExceptionInterface
     */
    public function normalize(array $config): array
    {
        try {
            return $this->processor->processConfiguration($this->configuration, $config);
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new Configurator\InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function validate(array $config): bool
    {
        try {
            $this->normalize($config);

            return true;
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            return false;
        }
    }

    private function buildFactoryNode(string $name): Node\Expr
    {
        if (($position = strpos($name, '::')) === false) {
            return new Node\Expr\New_(
                new Node\Name\FullyQualified($name),
            );
        } else {
            return new Node\Expr\StaticCall(
                new Node\Name\FullyQualified(substr($name, 0, $position)),
                new Node\Identifier(substr($name, $position + 2)),
            );
        }
    }

    public function compile(array $config): Repository\Client
    {
        try {
            $clientBuilder = new Sylius\Builder\Client(
                new Node\Scalar\String_($config['api_url']),
                new Node\Scalar\String_($config['client_id']),
                new Node\Scalar\String_($config['secret']),
            );

            if (isset($config['context'])) {
                if (isset($config['context']['http_client'])) {
                    $clientBuilder->withHttpClient($this->buildFactoryNode($config['context']['http_client']));
                }
                if (isset($config['context']['http_request_factory'])) {
                    $clientBuilder->withHttpRequestFactory($this->buildFactoryNode($config['context']['http_request_factory']));
                }
                if (isset($config['context']['http_stream_factory'])) {
                    $clientBuilder->withHttpStreamFactory($this->buildFactoryNode($config['context']['http_stream_factory']));
                }
                if (isset($config['context']['filesystem'])) {
                    $clientBuilder->withFileSystem($this->buildFactoryNode($config['context']['filesystem']));
                }
            }

            if (isset($config['password'])) {
                $clientBuilder->withPassword(
                    new Node\Scalar\String_($config['username']),
                    new Node\Scalar\String_($config['password']),
                );
            } elseif (isset($config['refresh_token'])) {
                $clientBuilder->withToken(
                    new Node\Scalar\String_($config['token']),
                    new Node\Scalar\String_($config['refresh_token']),
                );
            }

            return new Repository\Client($clientBuilder);
        } catch (Sylius\MissingAuthenticationMethodException $exception) {
            throw new Configurator\InvalidConfigurationException(
                message: 'Your Sylius API configuration is missing an authentication method, you should either define "username" or "token" options.',
                previous: $exception,
            );
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new Configurator\InvalidConfigurationException(
                message: $exception->getMessage(),
                previous: $exception
            );
        }
    }
}
