<?php

namespace Pikart\Goip\Contracts;

use Pikart\Goip\Message;
use Pikart\Goip\Request;

interface MessageFactory
{
    public function make( Request $request ) : Message;
}
