<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

/**
 * @package Pikart\Goip\Messages
 */
class HangupMessage extends Message
{
    /**
     * Ack message
     *
     * @return string|null
     */
    public function ack(): ?string
    {
        return "HANGUP " . $this->request()->get('hangup') . ' OK';
    }

    /**
     * Hangup count number
     *
     * @return int|null
     */
    public function hangup(): ?int
    {
        return $this->request()->getAsInt('hangup');
    }

    /**
     * Phone number
     *
     * @return string|null
     */
    public function num(): ?string
    {
        return $this->request()->get('num');
    }
}
