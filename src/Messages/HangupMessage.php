<?php
namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class HangupMessage extends Message
{
    /**
     * Ack message
     *
     * @return string|null
     */
    public function ack(): ? string
    {
        return "HANGUP ".$this->request()->get('hangup').' OK';
    }

    /**
     * Hangup count number
     *
     * @return int|null
     */
    public function hangup() : ? int
    {
        return $this->request()->get('hangup');
    }

    /**
     * Goip gateway id
     *
     * @return string|null
     */
    public function id() : ? string
    {
        return $this->request()->get('id');
    }

    /**
     * Goip password
     *
     * @return string|null
     */
    public function password() : ? string
    {
        return $this->request()->get('password');
    }

    /**
     * Phone number
     *
     * @return string|null
     */
    public function num() : ? string
    {
        return $this->request()->get('num');
    }

}
