<?php

namespace Ketyl\Vine;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * Global container instance.
     *
     * @var \Ketyl\Vine\Container
     */
    protected static Container $instance;

    /**
     * List of registered classes.
     *
     * @var array[]
     */
    protected array $classes = [];

    /**
     * List of registered instances.
     *
     * @var object[]
     */
    protected array $instances = [];

    /**
     * Set the global container instance.
     *
     * @param \Ketyl\Vine\Container $container
     * @return \Ketyl\Vine\Container
     */
    public static function setGlobal(Container $container): Container
    {
        return static::$instance = $container;
    }

    /**
     * Get the global Container instance.
     *
     * @return Container
     */
    public static function getGlobal(): Container
    {
        return static::$instance;
    }

    /**
     * Register a class to the container.
     *
     * @param string $name
     * @param string $class
     * @param mixed[] $arguments
     * @return void
     */
    public function register(string $name, string $class, array $arguments = [], mixed $callback = null): void
    {
        unset($this->classes[$name]);

        if (!class_exists($class)) {
            return;
        }

        $this->classes[$name] = [$class, $arguments, $callback];
    }

    /**
     * Bind an instance of an object to the container.
     *
     * @param string $name
     * @param mixed $callback
     * @return void
     */
    public function bind(string $name, mixed $callback)
    {
        $this->instances[$name] = $data = $callback();
        $this->classes[$name] = [get_class($data), [], $callback];
    }

    /**
     * Get an instance of a class from the container.
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name): mixed
    {
        if (!isset($this->classes[$name])) {
            return null;
        }

        $class = $this->classes[$name];

        if (!isset($this->instances[$name])) {
            $this->instances[$name] = new $class[0](...$class[1]);
        }

        if (is_callable($class[2])) {
            call_user_func($class[2], $this->instances[$name]);
        }

        return $this->instances[$name];
    }

    /**
     * Determine if the container includes specified item.
     *
     * @param string $name
     * @return boolean
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->classes);
    }

    /**
     * Get the list of registered classes.
     *
     * @return array[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * Get the list of created instances.
     *
     * @return object[]
     */
    public function getInstances(): array
    {
        return $this->instances;
    }
}
