<?php

namespace Pikart\Goip;

use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageListener;

class DefaultMessageDispatcher implements MessageDispatcher
{
    /**
     * The registered message listeners.
     * @var array
     */
    private array $listeners = [
        Message::class => []
    ];

    /**
     * Register an message listener with the dispatcher for concrete message.
     *
     * @param string $type
     * @param object $listener
     * @return string
     */
    public function listen( string $type, object $listener ) : string
    {
        if( !is_callable( $listener ) && !$listener instanceof MessageListener )
        {
            throw new \InvalidArgumentException("Listener must be object that is callable or implements MessageListener contract");
        }

        $id = $this->getHashId( $listener );
        $this->listeners[ $type ][ $id ] = $listener;
        return $id;
    }

    /**
     * Register an message listener with the dispatcher for all messages
     *
     * @param object $listener
     * @return string
     */
    public function listenAll( object $listener ) : string
    {
        return $this->listen( Message::class, $listener );
    }

    /**
     * Get all register listeners
     *
     * @return array
     */
    public function listeners(): array
    {
        return $this->listeners;
    }

    /**
     * Remove all registered listeners
     *
     * @param string|null $type
     */
    public function removeAll( ? string $type = null ) : void
    {
        if( $type && array_key_exists( $type, $this->listeners ) )
        {
            unset( $this->listeners[ $type ] );
            return;
        }

        $this->listeners = [];
    }

    /**
     * Remove listener by id
     *
     * @param string $id
     */
    public function remove( string $id ) : void
    {
        foreach ( $this->listeners as $type => $listeners )
        {
            if( is_array( $listeners ) && array_key_exists( $id, $listeners ) )
            {
                unset( $this->listeners[ $type ][ $id ] );
            }
        }
    }

    /**
     * Get listener by id
     *
     * @param string $id
     * @return object|null
     */
    public function get( string $id ) : ? object
    {
        foreach ( $this->listeners as $listenerType )
        {
            if( is_array( $listenerType ) && array_key_exists( $id, $listenerType ) )
            {
                return $listenerType[ $id ];
            }
        }

        return null;
    }

    /**
     * Dispatch messages to listeners
     *
     * @param Message $message
     */
    public function dispatch( Message $message ) : void
    {

        $listeners = $this->listeners[ Message::class ];

        $type = get_class( $message );

        if( $type !== Message::class && key_exists($type, $this->listeners ) )
        {
            $listeners = array_merge( $listeners, $this->listeners[ $type ]);
        }

        foreach ( $listeners as $listener )
        {

            if ($listener instanceof MessageListener)
            {
                $listener->onMessage($message);
                continue;
            }

            if (is_callable($listener))
            {
                $listener($message);
            }

        }
    }

    /**
     * Create unique id
     *
     * @param object $callable
     * @return string
     */
    private function getHashId( object $callable ) : string
    {
        return spl_object_hash( $callable );
    }

}
