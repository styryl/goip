<?php

declare(strict_types=1);

namespace Pikart\Goip\Contracts;

interface Sms
{
    /**
     * @return mixed[]
     */
    public function send(string $number, string $message): array;
}
