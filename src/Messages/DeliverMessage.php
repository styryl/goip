<?php
namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class DeliverMessage extends Message
{
    /**
     * Ack message
     *
     * @return string|null
     */
    public function ack() : ? string
    {
        return "DELIVER ".$this->request()->get('deliver').' OK';
    }

    /**
     * Deliver count number
     *
     * @return int|null
     */
    public function deliver() : ? int
    {
        return $this->request()->get('deliver');
    }

    /**
     * Sms count number
     *
     * @return int|null
     */
    public function smsNo() : ? int
    {
        return $this->request()->get('sms_no');
    }

    /**
     * Goip gateway state
     *
     * @return int|null
     */
    public function state() : ? int
    {
        return $this->request()->get('state');
    }

    /**
     * Phone number to which the sms was sent
     *
     * @return string|null
     */
    public function num() : ? string
    {
        return $this->request()->get('num');
    }
}
