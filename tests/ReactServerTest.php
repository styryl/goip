<?php

namespace Pikart\Goip\Tests;
use Pikart\Goip\DefaultMessageDispatcher;
use Pikart\Goip\DefaultMessageFactory;
use Pikart\Goip\Message;
use Pikart\Goip\ReactServer;
use React\EventLoop\LoopInterface;

class ReactServerTest extends TestCase
{
    private string $host = '127.0.0.1';
    private int $port = 3333;
    private GoipClient $client;

    public function setUp(): void
    {
        $this->client = new GoipClient($this->host, $this->port);
    }

    public function testItCanDispatchMessage() : void
    {
        $server = $this->createServer();

        $msg = null;

        $server->listenAll(function ( Message $message ) use (&$msg) {
            $msg = $message;
        });

        $loop = $this->invokeMethod( $server,  'createSocket');

        $message = "test echo message";
        $send = $this->client->sendTo($message);

        $loop->addTimer(0.1, function () use ( $loop ) {
            $loop->stop();
        });

        $loop->run();

        $this->assertInstanceOf( Message::class, $msg );
        $this->assertEquals( $message, $msg->request()->buffer() );
    }

    private function createServer() : ReactServer
    {
        $server = new ReactServer();
        $server->setHost( $this->host );
        $server->setPort( $this->port );
        $server->setMessageDispatcher( new DefaultMessageDispatcher() );
        $server->setMessageFactory( new DefaultMessageFactory() );

        return $server;
    }
}
