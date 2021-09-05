<?php

namespace Ketyl\Vine;

class Response
{
    /**
     * Create a new response.
     *
     * @param mixed $data
     * @param int $status
     */
    public function __construct(
        protected mixed $data,
        protected int $status = 200,
    ) {
        $this->data = $data;
        $this->status = $status;
    }

    /**
     * Transform the response into a string.
     *
     * @return string|null
     */
    public function transform(): string|null
    {
        if (!$this->data) {
            return null;
        }

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

    /**
     * Emit the response.
     *
     * @return void
     */
    public function emit()
    {
        http_response_code($this->status);

        echo $this->transform();
    }

    /**
     * Get the raw data of the response.
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }
}
