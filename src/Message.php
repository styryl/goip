<?php

declare(strict_types=1);

namespace Pikart\Goip;

abstract class Message
{
    /**
     * The goip Request
     */
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get request
     */
    public function request(): Request
    {
        return $this->request;
    }

    /**
     * Get parsed attributes
     *
     * @return mixed[]
     */
    public function attributes(): array
    {
        return $this->request->all();
    }

    /**
     * Get goip id
     */
    public function id(): ?string
    {
        return $this->request()
            ->get('id');
    }

    /**
     * Goip password
     */
    public function password(): ?string
    {
        return $this->request()
            ->get('password');
    }

    /**
     * Get ACK message
     */
    abstract public function ack(): ?string;
}
