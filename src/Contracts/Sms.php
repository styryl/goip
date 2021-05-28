<?php

declare(strict_types=1);

namespace Pikart\Goip\Contracts;

/**
 * @package Pikart\Goip\Contracts
 */
interface Sms
{
    /**
     * @param string $number
     * @param string $message
     * @return mixed[]
     */
    public function send(string $number, string $message): array;
}
