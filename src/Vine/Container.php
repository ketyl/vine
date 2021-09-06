<?php

namespace Ketyl\Vine;

class Container
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
    protected array $classes;

    /**
     * List of registered instances.
     *
     * @var object[]
     */
    protected array $instances;

    /**
     * Create a new Container instance.
     */
    public function __construct()
    {
        $this->classes = [];
        $this->instances = [];
    }

    /**
     * Set the global container instance.
     *
     * @param \Ketyl\Vine\Container $container
     * @return \Ketyl\Vine\Container
     */
    public static function setInstance(Container $container): Container
    {
        return static::$instance = $container;
    }

    /**
     * Get the global Container instance.
     *
     * @return Container
     */
    public static function getInstance(): Container
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
    public function register(string $name, string $class, array $arguments = [])
    {
        unset($this->classes[$name]);

        if (!class_exists($class)) {
            return;
        }

        $this->classes[$name] = [$name, $class, $arguments];
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

        if (!isset($this->instances[$name])) {
            $class = $this->classes[$name];

            $this->instances[$name] = new $class[1](...$class[2]);
        }

        return $this->instances[$name];
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
