<?php

namespace Pikart\Goip\Tests;

use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageFactory;
use Pikart\Goip\Contracts\ServerBuilder;
use Pikart\Goip\Contracts\ServerMaker;
use Pikart\Goip\ServerFactory;
use Pikart\Goip\Server;

class ServerFactoryTest extends TestCase
{
    public function testItCanMakeServer() : void
    {
        $messageDispatcherMock = $this->getMockBuilder( MessageDispatcher::class )->getMock();
        $messageFactoryMock = $this->getMockBuilder( MessageFactory::class )->getMock();
        $serverMock = $this->getMockBuilder( Server::class )->getMock();

        $serverFactory = new ServerFactory( $messageFactoryMock, $messageDispatcherMock );
        $server = $serverFactory->make( get_class( $serverMock ), '0.0.0.0', 0 );

        $this->assertInstanceOf( Server::class, $server );
    }


    public function testItCanMakeDefaultServer() : void
    {
        $serverMock = $this->getMockBuilder( Server::class )->getMock();

        $server = ServerFactory::default( get_class( $serverMock ), '0.0.0.0', 0 );
        $this->assertInstanceOf( Server::class, $server );
    }
}


