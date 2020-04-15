<?php

namespace Pikart\Goip;

abstract class Message
{
    /**
     * The goip Request
     *
     * @var Request
     */
    private Request $request;

    /**
     * Message constructor.
     *
     * @param Request $request
     */
    public function __construct( Request $request )
    {
        $this->request = $request;
    }

    /**
     * Get request
     *
     * @return Request
     */
    public function request() : Request
    {
        return $this->request;
    }

    /**
     * Get parsed attributes
     *
     * @return array
     */
    public function attributes() : array
    {
        return $this->request->all();
    }

    /**
     * Get ACK message
     *
     * @return string|null
     */
    abstract public function ack() : ? string;

}
