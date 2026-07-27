<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class ReceiveMessage extends Message
{
    public function ack(): ?string
    {
        return 'RECEIVE ' . $this->request()->get('receive') . ' OK';
    }

    public function receive(): ?int
    {
        return $this->request()
            ->getAsInt('receive');
    }

    public function srcnum(): ?int
    {
        return $this->request()
            ->getAsInt('srcnum');
    }

    public function msg(): ?string
    {
        return $this->request()
            ->get('msg');
    }
}
