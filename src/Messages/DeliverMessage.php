<?php
namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class DeliverMessage extends Message
{
    public function ack() : ? string
    {
        return "DELIVER ".$this->request->get('deliver').' OK';
    }

    public function deliver() : ? int
    {
        return $this->request->get('deliver');
    }

    public function id() : ? string
    {
        return $this->request->get('id');
    }

    public function password() : ? string
    {
        return $this->request->get('password');
    }

    public function smsNo() : ? int
    {
        return $this->request->get('sms_no');
    }

    public function state() : ? int
    {
        return $this->request->get('state');
    }

    public function num() : ? string
    {
        return $this->request->get('num');
    }
}
