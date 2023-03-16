<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Factory\Repository;

use Kiboko\Contract\Configurator\RepositoryInterface;
use Kiboko\Contract\Packaging\FileInterface;
use Kiboko\Plugin\Sylius\Builder;
use Kiboko\Plugin\Sylius\Factory\Repository;
use PhpParser\Builder as Capacity;
use PhpParser\Node;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversNothing]
/**
 * @internal
 *
 * @coversNothing
 */
final class LoaderTest extends TestCase
{
    public function fileMock(string $filename): FileInterface
    {
        $file = $this->createMock(FileInterface::class);

        $file->method('getPath')
            ->willReturn($filename)
        ;

        $file->method('asResource')
            ->willReturn(fopen('php://temp', 'w+'))
        ;

        return $file;
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function mergeWithPackages(): void
    {
        $capacity = $this->createMock(Capacity::class);

        $capacity->method('getNode')->willReturn(new Node\Stmt\Nop());

        $builder = new Builder\Loader($capacity);

        $child = $this->createMock(RepositoryInterface::class);

        $child->method('getFiles')->willReturn([]);
        $child->method('getPackages')->willReturn(['baz/baz']);

        $repository = new Repository\Loader($builder);

        $repository->addPackages('foo/foo', 'bar/bar:^1.0');

        $repository->merge($child);

        $this->assertCount(3, $repository->getPackages());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function mergeWithFiles(): void
    {
        $capacity = $this->createMock(Capacity::class);

        $capacity->method('getNode')->willReturn(new Node\Stmt\Nop());

        $builder = new Builder\Loader($capacity);

        $child = $this->createMock(RepositoryInterface::class);

        $child->method('getFiles')->willReturn([
            $this->fileMock('baz.php'),
        ]);
        $child->method('getPackages')->willReturn([]);

        $repository = new Repository\Loader($builder);

        $repository->addFiles(
            $this->fileMock('foo.php'),
            $this->fileMock('bar.php'),
        );

        $repository->merge($child);

        $this->assertCount(3, $repository->getFiles());
    }
}
