<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class HangupMessage extends Message
{
    public function ack(): ?string
    {
        return 'HANGUP ' . $this->request()->get('hangup') . ' OK';
    }

    public function hangup(): ?int
    {
        return $this->request()
            ->getAsInt('hangup');
    }

    public function num(): ?string
    {
        return $this->request()
            ->get('num');
    }

    public function cause(): ?int
    {
        return $this->request()
            ->getAsInt('cause');
    }
}
