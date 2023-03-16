<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Builder\Capacity;

use Kiboko\Plugin\Sylius\Builder\Capacity\All;
use Kiboko\Plugin\Sylius\MissingEndpointException;
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
final class AllTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function withoutEndpoint(): void
    {
        $capacity = new All();

        $this->expectException(MissingEndpointException::class);
        $this->expectExceptionMessage('Please check your capacity builder, you should have selected an endpoint.');

        $capacity->getNode();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function withEndpoint(): void
    {
        $capacity = new All();

        $capacity->withEndpoint(new Node\Identifier('foo'));

        $this->assertInstanceOf(Node\Stmt\Expression::class, $capacity->getNode());
    }
}
