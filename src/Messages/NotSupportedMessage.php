<?php

namespace Pikart\Goip\Messages;
use Pikart\Goip\Message;

class NotSupportedMessage extends Message
{
    public function ack(): ? string
    {
        return null;
    }
}
