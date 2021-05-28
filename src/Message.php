<?php

declare(strict_types=1);

namespace Pikart\Goip;

/**
 * Class Message
 * @package Pikart\Goip
 */
abstract class Message
{
    /**
     * The goip Request
     *
     * @var Request
     */
    private Request $request;

    /**
     * Message constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get request
     *
     * @return Request
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
     *
     * @return string|null
     */
    public function id(): ?string
    {
        return $this->request()->get('id');
    }

    /**
     * Goip password
     *
     * @return string|null
     */
    public function password(): ?string
    {
        return $this->request()->get('password');
    }

    /**
     * Get ACK message
     *
     * @return string|null
     */
    abstract public function ack(): ?string;
}
