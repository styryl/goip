<?php

namespace Pikart\Goip;
use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageFactory;
use Pikart\Goip\Exceptions\ServerFactoryException;
use Pikart\Goip\Contracts\ServerFactory as ServerFactoryContract;
use ReflectionClass;

class ServerFactory implements ServerFactoryContract
{
    protected MessageFactory $messageFactory;
    protected MessageDispatcher $messageDispatcher;

    public function __construct( MessageFactory $messageFactory, MessageDispatcher $messageDispatcher )
    {
        $this->messageDispatcher = $messageDispatcher;
        $this->messageFactory = $messageFactory;
    }

    public function make( string $serverClass, string $host, int $port, array $args = [] ): Server
    {
        $server = $this->resolve( $serverClass, $args );
        $server->setHost($host);
        $server->setPort($port);
        $server->setMessageFactory( $this->messageFactory );
        $server->setMessageDispatcher( $this->messageDispatcher );
        return $server;
    }

    private function resolve( string $serverBuilderClass, array $args = [] ) : Server
    {
        $reflection = new ReflectionClass($serverBuilderClass);
        return ( $reflection->getConstructor() ) ? $reflection->newInstanceArgs($args) : $reflection->newInstance();
    }

    public static function default( string $serverClass, string $host, int $port, array $args = [] ) : Server
    {
        $factory = new ServerFactory( new DefaultMessageFactory(), new DefaultMessageDispatcher() );
        return $factory->make( $serverClass, $host, $port, $args );
    }

}
