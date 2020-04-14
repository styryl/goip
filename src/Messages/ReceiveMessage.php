<?php
namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class ReceiveMessage extends Message
{
    public function ack(): ? string
    {
        return "RECEIVE ".$this->request->get('receive').' OK';
    }

    public function receive() : ? int
    {
        return $this->request->get('receive');
    }

    public function id() : ? string
    {
        return $this->request->get('id');
    }

    public function password() : ? string
    {
        return $this->request->get('password');
    }

    public function srcnum() : ? int
    {
        return $this->request->get('srcnum');
    }

    public function msg() : ? string
    {
        return $this->request->get('msg');
    }
}
