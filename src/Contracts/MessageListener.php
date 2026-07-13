<?php

declare(strict_types=1);

namespace Pikart\Goip\Contracts;

use Pikart\Goip\Message;

interface MessageListener
{
    public function onMessage(Message $message): void;
}
