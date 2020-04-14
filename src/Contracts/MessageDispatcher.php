<?php

namespace Pikart\Goip\Contracts;

use Pikart\Goip\Message;

interface MessageDispatcher
{
    public function listenAll( object $listener ) : string;
    public function listen( string $type, object $listener ) : string;
    public function listeners() : array;
    public function dispatch( Message $message ) : void;
    public function removeAll( ? string $eventType = null ) : void;
    public function remove( string $id ) : void;
    public function get( string $id ) : ? object;
}
