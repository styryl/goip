<?php

declare(strict_types=1);

namespace Pikart\Goip;

/**
 * @package Pikart\Goip
 */
class Request
{
    /**
     * The raw message from client
     * @var string
     */
    private string $buffer;

    /**
     * The remote host
     *
     * @var string
     */
    private string $host;

    /**
     * The remote port
     * @var int
     */
    private int $port;

    /**
     * Parsed attributes from raw buffer
     *
     * @var mixed[]
     */
    private array $attributes;

    /**
     * Request constructor.
     *
     * @param string $buffer
     * @param string $host
     * @param int $port
     */
    public function __construct(string $buffer, string $host, int $port)
    {
        $this->buffer = $buffer;
        $this->host = $host;
        $this->port = $port;
        $this->attributes = $this->parse($this->buffer);
    }

    /**
     * Get remote host
     *
     * @return string
     */
    public function host(): string
    {
        return $this->host;
    }

    /**
     * Get remote port
     *
     * @return int
     */
    public function port(): int
    {
        return $this->port;
    }

    /**
     * Get raw buffer
     *
     * @return string
     */
    public function buffer(): string
    {
        return $this->buffer;
    }

    /**
     * Get all attributes
     *
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->attributes;
    }

    /**
     * Check if attribute exists
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Get concrete attribute from attributes
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->has($key) ? $this->attributes[$key] : null;
    }

    /**
     * @param string $key
     * @return int|null
     */
    public function getAsInt(string $key): ?int
    {
        return $this->has($key) ? (int)$this->attributes[$key] : null;
    }

    /**
     * Parse raw buffer to attributes
     *
     * @param string $buffer
     * @return mixed[]
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
