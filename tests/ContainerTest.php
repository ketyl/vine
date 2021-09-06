<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Container;
use Ketyl\Vine\Tests\Stubs\User;

class ContainerTest extends TestCase
{
    /** @test */
    function can_create_container()
    {
        $container = new Container;

        $this->assertEmpty($container->getClasses());
        $this->assertEmpty($container->getInstances());
    }

    /** @test */
    function container_has_global_instance()
    {
        $container = new Container;

        Container::setInstance($container);

        $this->assertEquals($container, Container::getInstance());
    }

    /** @test */
    function can_register_class_to_container()
    {
        $container = new Container;

        $container->register('user', User::class);

        $this->assertInstanceOf(User::class, $container->get('user'));
    }

    /** @test */
    function can_register_class_with_arguments()
    {
        $container = new Container;

        $container->register('user', User::class, ['Zak', 'Nesler']);

        $this->assertInstanceOf(User::class, $container->get('user'));
        $this->assertEquals('Zak', $container->get('user')->firstName);
        $this->assertEquals('Nesler', $container->get('user')->lastName);
    }
}
