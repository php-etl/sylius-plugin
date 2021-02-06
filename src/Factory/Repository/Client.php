<?php declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Factory\Repository;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\Sylius;

final class Client implements Configurator\RepositoryInterface
{
    use RepositoryTrait;

    public function __construct(private Sylius\Builder\Client $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): Sylius\Builder\Client
    {
        return $this->builder;
    }

    public function merge(Configurator\RepositoryInterface $friend): self
    {
        array_push($this->files, ...$friend->getFiles());
        array_push($this->packages, ...$friend->getPackages());

        return $this;
    }
}
