<?php

namespace Ketyl\Vine;

class Response
{
    public function __construct(
        protected mixed $data,
        protected int $status = 200,
    ) {
        $this->data = $data;
        $this->status = $status;
    }

    public function transform(): mixed
    {
        if (!$this->data) {
            return null;
        }

        http_response_code($this->status);

        return match (gettype($this->data)) {
            'string' => $this->data,
            'array' => json_encode($this->data),
            'object' => match (get_class($this->data)) {
                \Ketyl\Vine\View::class => $this->data->getBody(),
                default => null,
            },
            default => null,
        };
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
