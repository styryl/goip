<?php

namespace Pikart\Goip;

abstract class Message
{
    protected Request $request;

    public function __construct( Request $request )
    {
        $this->request = $request;
    }

    public function request() : Request
    {
        return $this->request;
    }

    public function attributes() : array
    {
        return $this->request->all();
    }

    abstract public function ack() : ? string;

}
