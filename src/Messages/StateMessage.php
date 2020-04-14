<?php
namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class StateMessage extends Message
{
    public function ack(): ? string
    {
        return "STATE ".$this->request()->get('state').' OK';
    }

    public function state() : ? int
    {
        return $this->request()->get('state');
    }

    public function id() : ? string
    {
        return $this->request()->get('id');
    }

    public function password() : ? string
    {
        return $this->request()->get('password');
    }

    public function gsmRemainState() : ? string
    {
        return $this->request()->get('gsm_remain_state');
    }

}
