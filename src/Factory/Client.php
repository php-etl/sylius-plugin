<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Factory;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\Sylius;
use PhpParser\Node;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use function Kiboko\Component\SatelliteToolbox\Configuration\compileValueWhenExpression;

final readonly class Client implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    public function __construct(private ExpressionLanguage $interpreter)
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
        } catch (Symfony\InvalidConfigurationException|Symfony\InvalidTypeException $exception) {
            throw new Configurator\InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function validate(array $config): bool
    {
        try {
            $this->normalize($config);

            return true;
        } catch (Configurator\InvalidConfigurationException) {
            return false;
        }
    }

    private function buildFactoryNode(string $name): Node\Expr
    {
        if (($position = strpos($name, '::')) === false) {
            return new Node\Expr\New_(
                new Node\Name\FullyQualified($name),
            );
        }

        return new Node\Expr\StaticCall(
            new Node\Name\FullyQualified(substr($name, 0, $position)),
            new Node\Identifier(substr($name, $position + 2)),
        );
    }

    public function compile(array $config): Repository\Client
    {
        try {
            $clientBuilder = new Sylius\Builder\Client(
                compileValueWhenExpression($this->interpreter, $config['api_url']),
                compileValueWhenExpression($this->interpreter, $config['client_id']),
                compileValueWhenExpression($this->interpreter, $config['secret']),
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

            if (isset($config['api_type'])) {
                $clientBuilder->withApiType($config['api_type']);
            }

            if (isset($config['password'])) {
                $clientBuilder->withPassword(
                    compileValueWhenExpression($this->interpreter, $config['username']),
                    compileValueWhenExpression($this->interpreter, $config['password']),
                );
            } elseif (isset($config['refresh_token'])) {
                $clientBuilder->withToken(
                    compileValueWhenExpression($this->interpreter, $config['token']),
                    compileValueWhenExpression($this->interpreter, $config['refresh_token']),
                );
            }

            return new Repository\Client($clientBuilder);
        } catch (Sylius\MissingAuthenticationMethodException $exception) {
            throw new Configurator\InvalidConfigurationException(message: 'Your Sylius API configuration is missing an authentication method, you should either define "username" or "token" options.', previous: $exception);
        } catch (Symfony\InvalidConfigurationException|Symfony\InvalidTypeException $exception) {
            throw new Configurator\InvalidConfigurationException(message: $exception->getMessage(), previous: $exception);
        }
    }
}
