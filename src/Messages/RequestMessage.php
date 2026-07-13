<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class RequestMessage extends Message
{
    /**
     * Get ACK message
     */
    public function ack(): ?string
    {
        return 'req:' . $this->request()->get('req') . ';status:200;';
    }

    /**
     * Get Request count number
     */
    public function req(): ?int
    {
        return $this->request()
            ->getAsInt('req');
    }

    /**
     * Get goip password
     */
    public function password(): ?string
    {
        return $this->request()
            ->get('pass');
    }

    /**
     * Get SIM phone number
     */
    public function num(): ?string
    {
        return $this->request()
            ->get('num');
    }

    /**
     * Get GSM signal strength
     */
    public function signal(): ?int
    {
        return $this->request()
            ->getAsInt('signal');
    }

    /**
     * Get Login GSM status
     */
    public function gsmStatus(): ?string
    {
        return $this->request()
            ->get('gsm_status');
    }

    /**
     * Get Login VOIP status
     */
    public function voipStatus(): ?string
    {
        return $this->request()
            ->get('voip_status');
    }

    /**
     * Get VOIP state
     */
    public function voipState(): ?string
    {
        return $this->request()
            ->get('voip_state');
    }

    /**
     * Get Remain time
     */
    public function remainTime(): ?int
    {
        return $this->request()
            ->getAsInt('remain_time');
    }

    /**
     * Get gateway IMEI number
     */
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

    /**
     * Get IDLE time
     */
    public function idle(): ?int
    {
        return $this->request()
            ->getAsInt('idle');
    }

    /**
     * Get disable status
     */
    public function disableStatus(): ?int
    {
        return $this->request()
            ->getAsInt('disable_status');
    }

    /**
     * Get sms login
     */
    public function smsLogin(): ?string
    {
        return $this->request()
            ->get('sms_login');
    }

    /**
     * Get smbLogin
     */
    public function smbLogin(): ?string
    {
        return $this->request()
            ->get('smb_login');
    }

    /**
     * Get cellinfo
     */
    public function cellinfo(): ?string
    {
        return $this->request()
            ->get('cellinfo');
    }

    /**
     * Get cgatt
     */
    public function cgatt(): ?string
    {
        return $this->request()
            ->get('cgatt');
    }
}
