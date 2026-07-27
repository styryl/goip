<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class DeliverMessage extends Message
{
    public function ack(): ?string
    {
        return 'DELIVER ' . $this->request()->get('deliver') . ' OK';
    }

    public function deliver(): ?int
    {
        return $this->request()
            ->getAsInt('deliver');
    }

    public function smsNo(): ?int
    {
        return $this->request()
            ->getAsInt('sms_no');
    }

    public function state(): ?int
    {
        return $this->request()
            ->getAsInt('state');
    }

    public function num(): ?string
    {
        return $this->request()
            ->get('num');
    }
}
