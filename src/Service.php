<?php declare(strict_types=1);

namespace Kiboko\Plugin\Sylius;

use Kiboko\Contract\Configurator\ConfigurationExceptionInterface;
use Kiboko\Contract\Configurator\ConfiguratorExtractorInterface;
use Kiboko\Contract\Configurator\ConfiguratorLoaderInterface;
use Kiboko\Contract\Configurator\ConfiguratorPackagesInterface;
use Kiboko\Contract\Configurator\FactoryInterface;
use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Contract\Configurator\RepositoryInterface;
use Kiboko\Plugin\Sylius\Factory;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class Service implements FactoryInterface, ConfiguratorPackagesInterface, ConfiguratorLoaderInterface, ConfiguratorExtractorInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;
    private ExpressionLanguage $interpreter;

    public function __construct(?ExpressionLanguage $interpreter = null)
    {
        $this->processor = new Processor();
        $this->configuration = new Configuration();
        $this->interpreter = $interpreter ?? new ExpressionLanguage();
    }

    public function configuration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @throws ConfigurationExceptionInterface
     */
    public function normalize(array $config): array
    {
        try {
            return $this->processor->processConfiguration($this->configuration, $config);
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function validate(array $config): bool
    {
        try {
            $this->processor->processConfiguration($this->configuration, $config);

            return true;
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException) {
            return false;
        }
    }

    /**
     * @throws ConfigurationExceptionInterface
     */
    public function compile(array $config): RepositoryInterface
    {
        if (array_key_exists('expression_language', $config)
            && is_array($config['expression_language'])
            && count($config['expression_language'])
        ) {
            foreach ($config['expression_language'] as $provider) {
                $this->interpreter->registerProvider(new $provider);
            }
        }

        $clientFactory = new Factory\Client($this->interpreter);

        try {
            if (array_key_exists('extractor', $config)) {
                $extractorFactory = new Factory\Extractor($this->interpreter);

                $extractor = $extractorFactory->compile($config['extractor']);
                $extractorBuilder = $extractor->getBuilder();

                $client = $clientFactory->compile($config['client']);

                $extractorBuilder->withClient($client->getBuilder()->getNode());

                $extractor->merge($client);

                return $extractor;
            } elseif (array_key_exists('loader', $config)) {
                $loaderFactory = new Factory\Loader();

                $loader = $loaderFactory->compile($config['loader']);
                $loaderBuilder = $loader->getBuilder();

                $client = $clientFactory->compile($config['client']);

                $loaderBuilder->withClient($client->getBuilder()->getNode());

                $loader->merge($client);

                return $loader;
            } else {
                throw new InvalidConfigurationException(
                    'Could not determine if the factory should build an extractor or a loader.'
                );
            }
        } catch (MissingAuthenticationMethodException $exception) {
            throw new InvalidConfigurationException(
                'Your Sylius API configuration is missing an authentication method, you should either define "username" or "token" options.',
                0,
                $exception,
            );
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function getPackages(): array
    {
        return [
            'diglin/sylius-api-php-client',
            'laminas/laminas-diactoros',
            'php-http/guzzle7-adapter',
        ];
    }

    public function getExtractorKey(): string
    {
        return 'extractor';
    }

    public function getLoaderKeys(): array
    {
        return ['loader'];
    }
}
