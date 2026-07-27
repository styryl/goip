<?php

declare(strict_types=1);

namespace Pikart\Goip;

abstract class Message
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function request(): Request
    {
        return $this->request;
    }

    /**
     * @return mixed[]
     */
    public function attributes(): array
    {
        return $this->request->all();
    }

    public function id(): ?string
    {
        return $this->request()
            ->get('id');
    }

    public function password(): ?string
    {
        return $this->request()
            ->get('password');
    }

    abstract public function ack(): ?string;
}
