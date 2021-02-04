<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Builder\Capacity;

use functional\Kiboko\Plugin\Sylius\Builder\BuilderTestCase;
use Kiboko\Plugin\Sylius\Builder\Capacity\All;
use Kiboko\Plugin\Sylius\MissingEndpointException;
use PhpParser\Node;

final class AllTest extends BuilderTestCase
{
    public function testWithoutEndpoint()
    {
        $capacity = new All();

        $this->expectException(MissingEndpointException::class);
        $this->expectExceptionMessage('Please check your capacity builder, you should have selected an endpoint.');

        $capacity->getNode();
    }

    public function testWithEndpoint()
    {
        $capacity = new All();

        $capacity->withEndpoint(new Node\Identifier('foo'));

        $this->assertInstanceOf(Node\Stmt\Expression::class, $capacity->getNode());
    }
}
