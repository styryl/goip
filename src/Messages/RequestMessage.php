<?php
namespace Pikart\Goip\Messages;
use Pikart\Goip\Message;

class RequestMessage extends Message
{
    /**
     * Get ACK message
     *
     * @return string|null
     */
    public function ack(): ? string
    {
        return "REQUEST ".$this->request()->get('req').' OK';
    }

    /**
     * Get Request count number
     *
     * @return int|null
     */
    public function req() : ? int
    {
        return $this->request()->get('req');
    }

    /**
     * Get Goip id
     *
     * @return string|null
     */
    public function id() : ? string
    {
        return $this->request()->get('id');
    }

    /**
     * Get Goip password
     *
     * @return string|null
     */
    public function pass() : ? string
    {
        return $this->request()->get('pass');
    }

    /**
     * Get SIM phone number
     *
     * @return string|null
     */
    public function num() : ? string
    {
        return $this->request()->get('num');
    }

    /**
     * Get GSM signal strength
     *
     * @return int|null
     */
    public function signal() : ? int
    {
        return $this->request()->get('signal');
    }

    /**
     * Get Login GSM status
     *
     * @return string|null
     */
    public function gsmStatus() : ? string
    {
        return $this->request()->get('gsm_status');
    }

    /**
     * Get Login VOIP status
     *
     * @return string|null
     */
    public function voipStatus() : ? string
    {
        return $this->request()->get('voip_status');
    }

    /**
     * Get VOIP state
     *
     * @return string|null
     */
    public function voipState() : ? string
    {
        return $this->request()->get('voip_state');
    }

    /**
     * Get Remain time
     *
     * @return int|null
     */
    public function remainTime() : ? int
    {
        return $this->request()->get('remain_time');
    }

    /**
     * Get gateway IMEI number
     *
     * @return int|null
     */
    public function imei() : ? int
    {
        return $this->request()->get('imei');
    }

    /**
     * @return string|null
     */
    public function pro() : ? string
    {
        return $this->request()->get('pro');
    }

    /**
     * Get IDLE time
     * @return int|null
     */
    public function idle() : ? int
    {
        return $this->request()->get('idle');
    }

    /**
     * Get disable status
     *
     * @return int|null
     */
    public function disableStatus() : ? int
    {
        return $this->request()->get('disable_status');
    }

    /**
     * Get sms login
     *
     * @return string|null
     */
    public function smsLogin() : ? string
    {
        return $this->request()->get('sms_login');
    }

    /**
     * Get smbLogin
     *
     * @return string|null
     */
    public function smbLogin() : ? string
    {
        return $this->request()->get('smb_login');
    }

    /**
     * Get cellinfo
     *
     * @return string|null
     */
    public function cellinfo() : ? string
    {
        return $this->request()->get('cellinfo');
    }

    /**
     * Get cgatt
     *
     * @return string|null
     */
    public function cgatt() : ? string
    {
        return $this->request()->get('cgatt');
    }
}
