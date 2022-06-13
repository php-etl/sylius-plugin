<?php

return new class((new \Diglin\Sylius\ApiClient\SyliusLegacyClientFactory())->setHttpClient((function () {
    $client = new \Http\Mock\Client(new \Kiboko\Component\PHPUnitExtension\Mock\ResponseFactory());
    $client->on(new \Http\Message\RequestMatcher\RequestMatcher(path: '/api/oauth/v2/token', host: null, methods: ['POST'], schemes: []), include '/Users/clementzarch/PhpstormProjects/php-etl/sylius-plugin/tests/functional/Builder/Extractor/../token.php');
    $client->on(new \Http\Message\RequestMatcher\RequestMatcher(path: '/products', host: null, methods: ['GET'], schemes: []), include '/Users/clementzarch/PhpstormProjects/php-etl/sylius-plugin/tests/functional/Builder/Extractor/get-all-products.php');
    return $client;
})())->setRequestFactory(new \Kiboko\Component\PHPUnitExtension\Mock\RequestFactory())->setStreamFactory(new \Laminas\Diactoros\StreamFactory())->buildAuthenticatedByPassword('37_4f3e85115a1bd24df1fb430edc5a70a83febb909be850bb0', '7syb6i4x4mqcfo1wq45zkvmr8p2xdg0cjb9ymfm5jv1ifwm5uih1bbjzpfcmjs5i', 'josh62', 'Zk].#{rW]\':d4/`_1'), new \Psr\Log\NullLogger()) implements \Kiboko\Contract\Pipeline\ExtractorInterface
{
    public function __construct(public \Diglin\Sylius\ApiClient\SyliusLegacyClientInterface $client, public \Psr\Log\LoggerInterface $logger)
    {
    }
    public function extract() : iterable
    {
        try {
            (yield new \Kiboko\Component\Bucket\AcceptanceResultBucket(...$this->client->getProductsApi()->all(queryParameters: ['search' => (new \Diglin\Sylius\ApiClient\Search\SearchBuilder())->addFilter('parent', '=', '987qwerty')->getFilters()])));
        } catch (\Throwable $exception) {
            $this->logger->critical($exception->getMessage(), ['exception' => $exception]);
        }
    }
};
