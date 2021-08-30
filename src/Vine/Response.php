<?php

namespace Ketyl\Vine;

class Response
{
    protected $data;

    public function __construct(mixed $data)
    {
        $this->data = $data;
    }

    public function transform(): mixed
    {
        if (!$this->data) {
            return null;
        }

        return match (gettype($this->data)) {
            'string' => print($this->data),
            'array' => print(json_encode($this->data)),
        };
    }

    public function getData()
    {
        return $this->data;
    }
}
