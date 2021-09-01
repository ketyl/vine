<?php

namespace Ketyl\Vine;

class Response
{
    public function __construct(
        protected mixed $data
    ) {
        $this->data = $data;
    }

    public function transform(): mixed
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
