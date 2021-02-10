<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Builder\Capacity;

use functional\Kiboko\Plugin\Sylius\Builder\BuilderTestCase;
use Kiboko\Plugin\Sylius\Builder\Capacity\Update;
use Kiboko\Plugin\Sylius\MissingEndpointException;
use Kiboko\Plugin\Sylius\MissingParameterException;
use PhpParser\Node;

final class UpsertTest extends BuilderTestCase
{
    public function testWithoutEndpoint()
    {
        $capacity = new Update();

        $capacity->withCode(new Node\Scalar\String_('foo'));
        $capacity->withData(new Node\Expr\Array_());

        $this->expectException(MissingEndpointException::class);
        $this->expectExceptionMessage('Please check your capacity builder, you should have selected an endpoint.');

        $capacity->getNode();
    }

    public function testWithoutCode()
    {
        $capacity = new Update();

        $capacity->withEndpoint(new Node\Identifier('foo'));
        $capacity->withData(new Node\Expr\Array_());

        $this->expectException(MissingParameterException::class);
        $this->expectExceptionMessage('Please check your capacity builder, you should have provided a code.');

        $capacity->getNode();
    }

    public function testWithoutData()
    {
        $capacity = new Update();

        $capacity->withEndpoint(new Node\Identifier('foo'));
        $capacity->withCode(new Node\Scalar\String_('foo'));

        $this->expectException(MissingParameterException::class);
        $this->expectExceptionMessage('Please check your capacity builder, you should have provided some data.');

        $capacity->getNode();
    }

    public function testWithEndpoint()
    {
        $capacity = new Update();

        $capacity->withEndpoint(new Node\Identifier('foo'));
        $capacity->withCode(new Node\Scalar\String_('foo'));
        $capacity->withData(new Node\Expr\Array_());

        $this->assertInstanceOf(Node\Stmt\While_::class, $capacity->getNode());
    }
}
