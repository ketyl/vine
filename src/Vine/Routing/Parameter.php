<?php

namespace Ketyl\Vine\Routing;

class Parameter
{
    const DEFAULT_REGEX = '[^\/\{\}]+';

    public function __construct(
        protected string $name,
        protected mixed $value = null,
        protected string $regex = self::DEFAULT_REGEX,
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->regex = $regex;
    }

    public static function fromParts(string $name, string $regex = self::DEFAULT_REGEX)
    {
        return new static($name, null, $regex);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getRegex()
    {
        return $this->regex;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function setRegex(string $regex)
    {
        $this->regex = $regex;
    }
}
