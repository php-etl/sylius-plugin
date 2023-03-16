<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Builder\Capacity;

use Kiboko\Plugin\Sylius\Builder\Capacity\Upsert;
use Kiboko\Plugin\Sylius\MissingEndpointException;
use Kiboko\Plugin\Sylius\MissingParameterException;
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
final class UpsertTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function withoutEndpoint(): void
    {
        $capacity = new Upsert();

        $capacity->withCode(new Node\Scalar\String_('foo'));
        $capacity->withData(new Node\Expr\Array_());

        $this->expectException(MissingEndpointException::class);
        $this->expectExceptionMessage('Please check your capacity builder, you should have selected an endpoint.');

        $capacity->getNode();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function withoutCode(): void
    {
        $capacity = new Upsert();

        $capacity->withEndpoint(new Node\Identifier('foo'));
        $capacity->withData(new Node\Expr\Array_());

        $this->expectException(MissingParameterException::class);
        $this->expectExceptionMessage('Please check your capacity builder, you should have provided a code.');

        $capacity->getNode();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function withoutData(): void
    {
        $capacity = new Upsert();

        $capacity->withEndpoint(new Node\Identifier('foo'));
        $capacity->withCode(new Node\Scalar\String_('foo'));

        $this->expectException(MissingParameterException::class);
        $this->expectExceptionMessage('Please check your capacity builder, you should have provided some data.');

        $capacity->getNode();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function withEndpoint(): void
    {
        $capacity = new Upsert();

        $capacity->withEndpoint(new Node\Identifier('foo'));
        $capacity->withCode(new Node\Scalar\String_('foo'));
        $capacity->withData(new Node\Expr\Array_());

        $this->assertInstanceOf(Node\Stmt\While_::class, $capacity->getNode());
    }
}
