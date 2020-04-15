<?php
namespace Pikart\Goip\Messages;

use Pikart\Goip\Message;

class RecordMessage extends Message
{
    /**
     * Ack message
     *
     * @return string|null
     */
    public function ack(): ? string
    {
        return "RECORD ".$this->request()->get('record').' OK';
    }

    /**
     * Record count number
     *
     * @return int|null
     */
    public function record() : ? int
    {
        return $this->request()->get('record');
    }

    /**
     * @return int|null
     */
    public function dir() : ? int
    {
        return $this->request()->get('dir');
    }

    /**
     * Phone number
     *
     * @return string|null
     */
    public function num() : ? string
    {
        return $this->request()->get('num');
    }
}
