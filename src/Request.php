<?php

declare(strict_types=1);

namespace Pikart\Goip;

class Request
{
    /**
     * The raw message from client
     */
    private string $buffer;

    /**
     * The remote host
     */
    private string $host;

    /**
     * The remote port
     */
    private int $port;

    /**
     * Parsed attributes from raw buffer
     *
     * @var array<string, string>
     */
    private array $attributes;

    public function __construct(string $buffer, string $host, int $port)
    {
        $this->buffer = $buffer;
        $this->host = $host;
        $this->port = $port;
        $this->attributes = $this->parse($this->buffer);
    }

    /**
     * Get remote host
     */
    public function host(): string
    {
        return $this->host;
    }

    /**
     * Get remote port
     */
    public function port(): int
    {
        return $this->port;
    }

    /**
     * Get raw buffer
     */
    public function buffer(): string
    {
        return $this->buffer;
    }

    /**
     * Get all attributes
     *
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->attributes;
    }

    /**
     * Check if attribute exists
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Get concrete attribute from attributes
     */
    public function get(string $key): ?string
    {
        return $this->has($key) ? $this->attributes[$key] : null;
    }

    public function getAsInt(string $key): ?int
    {
        return $this->has($key) ? (int) $this->attributes[$key] : null;
    }

    /**
     * Parse raw buffer to attributes
     *
     * @return array<string, string>
     */
    private function parse(string $buffer): array
    {
        $arr = explode(';', $buffer);
        $data = [];
        foreach ($arr as $value) {
            $parts = explode(':', $value);
            $key = array_shift($parts);
            $val = implode(':', $parts);

            if (strlen($key) === 0) {
                continue;
            }

            $data[mb_strtolower($key)] = $val;
        }

        return $data;
    }
}
