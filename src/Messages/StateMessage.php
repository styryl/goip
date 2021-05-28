<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

/**
 * @package Pikart\Goip\Messages
 */
class StateMessage extends Message
{
    public function ack(): ?string
    {
        return "STATE " . $this->request()->get('state') . ' OK';
    }

    /**
     * Get state count number
     *
     * @return int|null
     */
    public function state(): ?int
    {
        return $this->request()->getAsInt('state');
    }

    /**
     * Get GSM line state
     *
     * @return string|null
     */
    public function gsmRemainState(): ?string
    {
        return $this->request()->get('gsm_remain_state');
    }
}
