<?php

namespace Pikart\Goip\Messages;
use Pikart\Goip\Message;

class NotSupportedMessage extends Message
{
    /**
     * Ack message
     *
     * @return string|null
     */
    public function ack(): ? string
    {
        return null;
    }
}
