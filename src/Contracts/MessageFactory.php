<?php

declare(strict_types=1);

namespace Pikart\Goip\Contracts;

use Pikart\Goip\Message;
use Pikart\Goip\Request;

/**
 * @package Pikart\Goip\Contracts
 */
interface MessageFactory
{
    /**
     * @param Request $request
     * @return Message
     */
    public function make(Request $request): Message;
}
