<?php
namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class RecordMessage extends Message
{
    public function ack(): ? string
    {
        return "RECORD ".$this->request->get('record').' OK';
    }

    public function record() : ? int
    {
        return $this->request->get('record');
    }

    public function id() : ? string
    {
        return $this->request->get('id');
    }

    public function password() : ? string
    {
        return $this->request->get('password');
    }

    public function dir() : ? int
    {
        return $this->request->get('dir');
    }

    public function num() : ? string
    {
        return $this->request->get('num');
    }
}
