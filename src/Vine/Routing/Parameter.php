<?php

namespace Ketyl\Vine\Routing;

class Parameter
{
    const DEFAULT_PATTERN = '[^\/\{\}]+';

    public function __construct(
        protected string $name,
        protected mixed $value = null,
        protected string $pattern = self::DEFAULT_PATTERN,
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->pattern = $pattern;
    }

    public static function fromParts(string $name, string $pattern = self::DEFAULT_PATTERN)
    {
        return new static($name, null, $pattern);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function setPattern(string $pattern)
    {
        $this->pattern = $pattern;
    }
}
