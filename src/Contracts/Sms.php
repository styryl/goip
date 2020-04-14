<?php

namespace Pikart\Goip\Contracts;

interface Sms
{
    public function send( string $number, string $message ) : array;
}
