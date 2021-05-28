<?php

declare(strict_types=1);

namespace Pikart\Goip\Contracts;

use Pikart\Goip\Message;

/**
 * @package Pikart\Goip\Contracts
 */
interface MessageListener
{
    /**
     * @param Message $message
     */
    public function onMessage(Message $message): void;
}
