<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class RequestMessage extends Message
{
    public function ack(): ?string
    {
        return 'reg:' . $this->request()->get('req') . ';status:0;';
    }

    public function req(): ?int
    {
        return $this->request()
            ->getAsInt('req');
    }

    public function password(): ?string
    {
        return $this->request()
            ->get('pass');
    }

    public function num(): ?string
    {
        return $this->request()
            ->get('num');
    }

    public function signal(): ?int
    {
        return $this->request()
            ->getAsInt('signal');
    }

    public function gsmStatus(): ?string
    {
        return $this->request()
            ->get('gsm_status');
    }

    public function voipStatus(): ?string
    {
        return $this->request()
            ->get('voip_status');
    }

    public function voipState(): ?string
    {
        return $this->request()
            ->get('voip_state');
    }

    public function remainTime(): ?int
    {
        return $this->request()
            ->getAsInt('remain_time');
    }

    public function imei(): ?int
    {
        return $this->request()
            ->getAsInt('imei');
    }

    public function pro(): ?string
    {
        return $this->request()
            ->get('pro');
    }

    public function idle(): ?int
    {
        return $this->request()
            ->getAsInt('idle');
    }

    public function disableStatus(): ?int
    {
        return $this->request()
            ->getAsInt('disable_status');
    }

    public function smsLogin(): ?string
    {
        return $this->request()
            ->get('sms_login');
    }

    public function smbLogin(): ?string
    {
        return $this->request()
            ->get('smb_login');
    }

    public function cellinfo(): ?string
    {
        return $this->request()
            ->get('cellinfo');
    }

    public function cgatt(): ?string
    {
        return $this->request()
            ->get('cgatt');
    }
}
