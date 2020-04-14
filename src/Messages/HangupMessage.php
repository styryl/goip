<?php
namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class HangupMessage extends Message
{
    public function ack(): ? string
    {
        return "HANGUP ".$this->request->get('hangup').' OK';
    }

    public function hangup() : ? int
    {
        return $this->request->get('hangup');
    }

    public function id() : ? string
    {
        return $this->request->get('id');
    }

    public function password() : ? string
    {
        return $this->request->get('password');
    }

    public function num() : ? string
    {
        return $this->request->get('num');
    }

}
