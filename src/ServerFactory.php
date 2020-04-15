<?php

namespace Pikart\Goip;
use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageFactory;
use Pikart\Goip\Exceptions\ServerFactoryException;
use Pikart\Goip\Contracts\ServerFactory as ServerFactoryContract;
use ReflectionClass;

class ServerFactory implements ServerFactoryContract
{
    /**
     * Message factory implementation
     *
     * @var MessageFactory
     */
    private MessageFactory $messageFactory;

    /**
     * Message dispatcher implementation
     *
     * @var MessageDispatcher
     */
    private MessageDispatcher $messageDispatcher;

    /**
     * ServerFactory constructor.
     *
     * @param MessageFactory $messageFactory
     * @param MessageDispatcher $messageDispatcher
     */
    public function __construct( MessageFactory $messageFactory, MessageDispatcher $messageDispatcher )
    {
        $this->messageDispatcher = $messageDispatcher;
        $this->messageFactory = $messageFactory;
    }

    /**
     * Make Server instance
     *
     * @param string $serverClass Implementation of Server
     * @param string $host Host to bind
     * @param int $port Port to bind
     * @param array $args Additional parameters
     * @return Server
     * @throws \ReflectionException
     */
    public function make( string $serverClass, string $host, int $port, array $args = [] ): Server
    {
        $server = $this->resolve( $serverClass, $args );
        $server->setHost($host);
        $server->setPort($port);
        $server->setMessageFactory( $this->messageFactory );
        $server->setMessageDispatcher( $this->messageDispatcher );
        return $server;
    }

    /**
     * Resolve Server instance
     *
     * @param string $serverClass
     * @param array $args
     * @return Server
     * @throws \ReflectionException
     */
    private function resolve( string $serverClass, array $args = [] ) : Server
    {
        $reflection = new ReflectionClass($serverClass);
        return ( $reflection->getConstructor() ) ? $reflection->newInstanceArgs($args) : $reflection->newInstance();
    }

    /**
     * Resolve default Server instance
     *
     * @param string $serverClass
     * @param string $host
     * @param int $port
     * @param array $args
     * @return Server
     *
     * @throws \ReflectionException
     */
    public static function default( string $serverClass, string $host, int $port, array $args = [] ) : Server
    {
        $factory = new ServerFactory( new DefaultMessageFactory(), new DefaultMessageDispatcher() );
        return $factory->make( $serverClass, $host, $port, $args );
    }

}
