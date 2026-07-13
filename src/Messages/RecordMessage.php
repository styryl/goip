<?php

declare(strict_types=1);

namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class RecordMessage extends Message
{
    /**
     * Ack message
     */
    public function ack(): ?string
    {
        return 'RECORD ' . $this->request()->get('record') . ' OK';
    }

    /**
     * Record count number
     */
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

    /**
     * Phone number
     */
    public function num(): ?string
    {
        return $this->request()
            ->get('num');
    }
}
