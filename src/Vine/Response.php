<?php

namespace Ketyl\Vine;

class Response
{
    protected $data;

    public function __construct(mixed $data)
    {
        $this->data = $data;
    }

    public function transform(): string
    {
        if (!$this->data) {
            return null;
        }

        return match (gettype($this->data)) {
            'string' => $this->data,
            'array' => json_encode($this->data),
        };
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
