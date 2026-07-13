<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class NotSupportedMessage extends Message
{
    /**
     * Ack message
     */
    public function ack(): ?string
    {
        return null;
    }
}
