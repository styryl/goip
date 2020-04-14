<?php

namespace Pikart\Goip\Contracts;

use Pikart\Goip\Message;

interface MessageListener
{
    public function onMessage( Message $message ) : void;
}
