<?php

declare(strict_types=1);

namespace Pikart\Goip;

class Request
{
    /**
     * @var array<string, string>
     */
    private array $attributes;

    public function __construct(
        private string $buffer,
        private string $host,
        private int $port
    ) {
        $this->attributes = $this->parse($buffer);
    }

    public function host(): string
    {
        return $this->host;
    }

    public function port(): int
    {
        return $this->port;
    }

    public function buffer(): string
    {
        return $this->buffer;
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->attributes;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function get(string $key): ?string
    {
        return $this->has($key) ? $this->attributes[$key] : null;
    }

    public function getAsInt(string $key): ?int
    {
        return $this->has($key) ? (int) $this->attributes[$key] : null;
    }

    /**
     * @return array<string, string>
     */
    private function parse(string $buffer): array
    {
        $arr = explode(';', $buffer);
        $data = [];
        foreach ($arr as $index => $value) {
            $parts = explode(':', $value);
            $key = array_shift($parts);
            $val = implode(':', $parts);

            if (strlen($key) === 0) {
                continue;
            }

            $key = mb_strtolower($key);

            if ($key === 'msg') {
                $data[$key] = implode(';', array_merge([$val], array_slice($arr, $index + 1)));
                break;
            }

            if ($key === 'num' && str_contains($val, ',cause:')) {
                [$val, $data['cause']] = explode(',cause:', $val, 2);
            }

            $data[$key] = $val;
        }

        return $data;
    }
}
