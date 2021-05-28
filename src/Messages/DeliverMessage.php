<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

/**
 * Class DeliverMessage
 * @package Pikart\Goip\Messages
 */
class DeliverMessage extends Message
{
    /**
     * Ack message
     *
     * @return string|null
     */
    public function ack(): ?string
    {
        return "DELIVER " . $this->request()->get('deliver') . ' OK';
    }

    /**
     * Deliver count number
     *
     * @return int|null
     */
    public function deliver(): ?int
    {
        return $this->request()->getAsInt('deliver');
    }

    /**
     * Sms count number
     *
     * @return int|null
     */
    public function smsNo(): ?int
    {
        return $this->request()->getAsInt('sms_no');
    }

    /**
     * Goip gateway state
     *
     * @return int|null
     */
    public function state(): ?int
    {
        return $this->request()->getAsInt('state');
    }

    /**
     * Phone number to which the sms was sent
     *
     * @return string|null
     */
    public function num(): ?string
    {
        return $this->request()->get('num');
    }
}
