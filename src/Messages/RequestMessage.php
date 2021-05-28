<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

/**
 * @package Pikart\Goip\Messages
 */
class RequestMessage extends Message
{
    /**
     * Get ACK message
     *
     * @return string|null
     */
    public function ack(): ?string
    {
        return 'req:'.$this->request()->get('req').';status:200;';
    }

    /**
     * Get Request count number
     *
     * @return int|null
     */
    public function req(): ?int
    {
        return $this->request()->getAsInt('req');
    }

    /**
     * Get goip password
     *
     * @return string|null
     */
    public function password(): ?string
    {
        return $this->request()->get('pass');
    }

    /**
     * Get SIM phone number
     *
     * @return string|null
     */
    public function num(): ?string
    {
        return $this->request()->get('num');
    }

    /**
     * Get GSM signal strength
     *
     * @return int|null
     */
    public function signal(): ?int
    {
        return $this->request()->getAsInt('signal');
    }

    /**
     * Get Login GSM status
     *
     * @return string|null
     */
    public function gsmStatus(): ?string
    {
        return $this->request()->get('gsm_status');
    }

    /**
     * Get Login VOIP status
     *
     * @return string|null
     */
    public function voipStatus(): ?string
    {
        return $this->request()->get('voip_status');
    }

    /**
     * Get VOIP state
     *
     * @return string|null
     */
    public function voipState(): ?string
    {
        return $this->request()->get('voip_state');
    }

    /**
     * Get Remain time
     *
     * @return int|null
     */
    public function remainTime(): ?int
    {
        return $this->request()->getAsInt('remain_time');
    }

    /**
     * Get gateway IMEI number
     *
     * @return int|null
     */
    public function imei(): ?int
    {
        return $this->request()->getAsInt('imei');
    }

    /**
     * @return string|null
     */
    public function pro(): ?string
    {
        return $this->request()->get('pro');
    }

    /**
     * Get IDLE time
     * @return int|null
     */
    public function idle(): ?int
    {
        return $this->request()->getAsInt('idle');
    }

    /**
     * Get disable status
     *
     * @return int|null
     */
    public function disableStatus(): ?int
    {
        return $this->request()->getAsInt('disable_status');
    }

    /**
     * Get sms login
     *
     * @return string|null
     */
    public function smsLogin(): ?string
    {
        return $this->request()->get('sms_login');
    }

    /**
     * Get smbLogin
     *
     * @return string|null
     */
    public function smbLogin(): ?string
    {
        return $this->request()->get('smb_login');
    }

    /**
     * Get cellinfo
     *
     * @return string|null
     */
    public function cellinfo(): ?string
    {
        return $this->request()->get('cellinfo');
    }

    /**
     * Get cgatt
     *
     * @return string|null
     */
    public function cgatt(): ?string
    {
        return $this->request()->get('cgatt');
    }
}
