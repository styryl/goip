<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class DeliverMessage extends Message
{
    /**
     * Ack message
     */
    public function ack(): ?string
    {
        return 'DELIVER ' . $this->request()->get('deliver') . ' OK';
    }

    /**
     * Deliver count number
     */
    public function deliver(): ?int
    {
        return $this->request()
            ->getAsInt('deliver');
    }

    /**
     * Sms count number
     */
    public function smsNo(): ?int
    {
        return $this->request()
            ->getAsInt('sms_no');
    }

    /**
     * Goip gateway state
     */
    public function state(): ?int
    {
        return $this->request()
            ->getAsInt('state');
    }

    /**
     * Phone number to which the sms was sent
     */
    public function num(): ?string
    {
        return $this->request()
            ->get('num');
    }
}
