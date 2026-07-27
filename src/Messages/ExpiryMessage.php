<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class ExpiryMessage extends Message
{
    public function ack(): ?string
    {
        return 'EXPIRY ' . $this->request()->get('expiry') . ' OK';
    }

    public function expiry(): ?int
    {
        return $this->request()
            ->getAsInt('expiry');
    }

    public function exp(): ?int
    {
        return $this->request()
            ->getAsInt('exp');
    }
}
