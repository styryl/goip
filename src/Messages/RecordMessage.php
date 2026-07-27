<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class RecordMessage extends Message
{
    public function ack(): ?string
    {
        return 'RECORD ' . $this->request()->get('record') . ' OK';
    }

    public function record(): ?int
    {
        return $this->request()
            ->getAsInt('record');
    }

    public function dir(): ?int
    {
        return $this->request()
            ->getAsInt('dir');
    }

    public function num(): ?string
    {
        return $this->request()
            ->get('num');
    }
}
