<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Capacity;

use Kiboko\Plugin\Sylius;
use PhpParser\Builder;
use PhpParser\Node;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use function Kiboko\Component\SatelliteToolbox\Configuration\compileValueWhenExpression;

final readonly class All implements CapacityInterface
{
    public function __construct(
        private readonly ExpressionLanguage $interpreter
    ) {
    }

    public function applies(array $config): bool
    {
        $endpoints = array_merge(
            Sylius\Validator\ExtractorConfigurationValidator::ADMIN_VALID_TYPES,
            Sylius\Validator\ExtractorConfigurationValidator::SHOP_VALID_TYPES,
        );

        return isset($config['type'])
            && \array_key_exists($config['type'], $endpoints)
            && isset($config['method'])
            && 'all' === $config['method'];
    }

    private function compileFilters(array ...$filters): Node\Expr
    {
        $builder = new Sylius\Builder\Search();
        foreach ($filters as $filter) {
            $builder->addFilter(
                field: compileValueWhenExpression($this->interpreter, $filter['field']),
                operator: compileValueWhenExpression($this->interpreter, $filter['operator']),
                value: compileValueWhenExpression($this->interpreter, $filter['value']),
                scope: \array_key_exists('scope', $filter) ? compileValueWhenExpression($this->interpreter, $filter['scope']) : null,
                locale: \array_key_exists('locale', $filter) ? compileValueWhenExpression($this->interpreter, $filter['locale']) : null
            );
        }

        return $builder->getNode();
    }

    public function getBuilder(array $config): Builder
    {
        $builder = (new Sylius\Builder\Capacity\All())
            ->withEndpoint(new Node\Identifier(sprintf('get%sApi', ucfirst((string) $config['type']))))
        ;

        if (isset($config['search']) && \is_array($config['search'])) {
            $builder->withSearch($this->compileFilters(...$config['search']));
        }

        return $builder;
    }
}
