<?php

namespace Pikart\Goip;

use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageFactory;

abstract class Server
{
    protected int $port;
    protected string $host;
    protected MessageDispatcher $messageDispatcher;
    protected MessageFactory $messageFactory;
    protected bool $stop = false;

    public function setPort( int $port ) : void
    {
        $this->port = $port;
    }

    public function setHost( string $host ) : void
    {
        $this->host = $host;
    }

    public function setMessageDispatcher( MessageDispatcher $dispatcher ) : void
    {
        $this->messageDispatcher = $dispatcher;
    }

    public function setMessageFactory( MessageFactory $factory ) : void
    {
        $this->messageFactory = $factory;
    }

    public function stop() : void
    {
        $this->stop = true;
    }

    public function listen( string $type, object $listener ): string
    {
        return $this->messageDispatcher->listen( $type, $listener );
    }

    public function listenAll( object $listener ) : string
    {
        return $this->messageDispatcher->listenAll(  $listener );
    }

    public function off( string $id ): void
    {
        $this->messageDispatcher->remove( $id );
    }

    public function dispatcher() : MessageDispatcher
    {
        return $this->messageDispatcher;
    }

    protected function dispatch( Request $request ) : void
    {
        $message = $this->messageFactory->make( $request );

        if( $message->ack() )
        {
            $this->send( $message->ack(), $message->request()->host(), $message->request()->port() );
        }

        $this->messageDispatcher->dispatch( $message );
    }

    abstract public function run() : void;
    abstract protected function send( string $message, string $host, int $port ) : void;
}
