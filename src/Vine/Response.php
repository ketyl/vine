<?php

namespace Ketyl\Vine;

class Response
{
    /**
     * The status code of the response.
     *
     * @var integer
     */
    protected int $status = 200;

    /**
     * The list of headers to apply to the response.
     *
     * @var mixed[]
     */
    protected array $headers = [];

    /**
     * The body of the response.
     *
     * @var string
     */
    protected string $body = '';

    /**
     * Transform data into a string.
     *
     * @return string
     */
    public function transform(mixed $data): string
    {
        if (is_array($data)) {
            $this->addHeader('Content-Type', 'application/json');
        }

        return match (gettype($data)) {
            'string' => $data,
            'array' => json_encode($data),
            'object' => match (get_class($data)) {
                \Ketyl\Vine\View::class => $data->getBody(),
                default => '',
            },
            default => '',
        };
    }

    /**
     * Send the response to the client.
     *
     * @return void
     */
    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->getHeaders() as $name => $value) {
            header(sprintf('%s: %s', $name, $value));
        }

        echo $this->getBody();
    }

    /**
     * Write data to the body.
     *
     * @return self
     */
    public function write(mixed $data): self
    {
        $this->body .= $this->transform($data);

        return $this;
    }

    /**
     * Get the body of the response.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get the value of headers.
     *
     * @return mixed[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the value of headers.
     *
     * @param mixed $name
     * @param mixed $value
     * @return self
     */
    public function addHeader(mixed $name, mixed $value = null): self
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->addHeader($key, $value);
            }
        } else {
            $this->headers[$name] = $value;
        }

        return $this;
    }

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set the status code.
     *
     * @param integer $status
     * @return self
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }
}
