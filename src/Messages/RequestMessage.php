<?php
namespace Pikart\Goip\Messages;
use Pikart\Goip\Message;

class RequestMessage extends Message
{
    public function ack(): ? string
    {
        return "RECORD ".$this->request()->get('req').' OK';
    }

    public function req() : ? int
    {
        return $this->request()->get('req');
    }

    public function id() : ? string
    {
        return $this->request()->get('id');
    }

    public function pass() : ? string
    {
        return $this->request()->get('pass');
    }

    public function num() : ? string
    {
        return $this->request()->get('num');
    }

    public function signal() : ? int
    {
        return $this->request()->get('signal');
    }

    public function gsmStatus() : ? string
    {
        return $this->request()->get('gsm_status');
    }

    public function voipStatus() : ? string
    {
        return $this->request()->get('voip_status');
    }

    public function voipState() : ? string
    {
        return $this->request()->get('voip_state');
    }

    public function remainTime() : ? int
    {
        return $this->request()->get('remain_time');
    }

    public function imei() : ? int
    {
        return $this->request()->get('imei');
    }

    public function pro() : ? string
    {
        return $this->request()->get('pro');
    }

    public function idle() : ? int
    {
        return $this->request()->get('idle');
    }

    public function disableStatus() : ? int
    {
        return $this->request()->get('disable_status');
    }

    public function smsLogin() : ? string
    {
        return $this->request()->get('sms_login');
    }

    public function smbLogin() : ? string
    {
        return $this->request()->get('smb_login');
    }

    public function cellinfo() : ? string
    {
        return $this->request()->get('cellinfo');
    }

    public function cgatt() : ? string
    {
        return $this->request()->get('cgatt');
    }
}
