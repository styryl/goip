<?php
namespace Pikart\Goip\Tests;
use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageListener;
use Pikart\Goip\DefaultMessageDispatcher;
use Pikart\Goip\DefaultMessageFactory;
use Pikart\Goip\Message;
use Pikart\Goip\Request;
use Pikart\Goip\UdpServer;

final class UdpServerTest extends TestCase
{
    private string $host = '127.0.0.1';
    private int $port = 333;
    private UdpServer $server;
    private GoipClient $client;
    private MessageListener $messageListener;
    private object $callback;

    public function setUp(): void
    {
        parent::setUp();

        $this->server = new UdpServer();

        $this->server->setHost( $this->host );
        $this->server->setPort( $this->port );
        $this->server->setMessageDispatcher( new DefaultMessageDispatcher() );
        $this->server->setMessageFactory( new DefaultMessageFactory() );

        $this->invokeMethod( $this->server,  'createSocket');

        $this->client = new GoipClient($this->host, $this->port);

        $this->messageListener = $this->getMockBuilder( MessageListener::class )
            ->disableOriginalConstructor()
            ->setMethods(['onMessage'])
            ->getMock();

        $this->callback = function ( Message $message ){};
    }

    public function testReceiveMethodShouldReturnRequestInstanceIfMessageIsReceived() : void
    {
        $message = "test echo message";

        $send = $this->client->sendTo($message);

        $request = $this->invokeMethod( $this->server,  'receive');

        $this->assertInstanceOf( Request::class, $request );
    }

    public function testReceiveMethodShouldReturnNullIfMessageIsNotReceived() : void
    {
        $request = $this->invokeMethod( $this->server,  'receive');
        $this->assertNull($request);
    }

    public function testItCanReceiveMessageFromClient() : void
    {
        $message = "test echo message";

        $send = $this->client->sendTo($message);

        $this->assertIsInt($send);

        $request = $this->invokeMethod( $this->server,  'receive');

        $this->assertEquals($message, $request->buffer() );
    }

    public function testItCanSendMessageToClient() : void
    {
        $message = "test echo message";

        $send = $this->client->sendTo($message);

        $this->assertIsInt($send);

        $request = $this->invokeMethod( $this->server,  'receive');

        $this->assertEquals($message, $request->buffer() );

        $this->invokeMethod( $this->server,  'send', [
            'message' => $message,
            'host'    => $request->host(),
            'port'    => $request->port(),
        ]);

        $this->assertEquals( $message, $this->client->receiveFrom() );
    }

    public function testItCanRegisterListenerForAllMessages() : void
    {
        $id = $this->server->listenAll( $this->messageListener );
        $this->assertIsString($id);

        $id2 = $this->server->listenAll( $this->callback );
        $this->assertIsString($id2);
    }

    public function testItCanRegisterListenerForConcreteMessage() : void
    {
        $id = $this->server->listen( Message::class, $this->messageListener);
        $this->assertIsString($id);

        $id2 = $this->server->listen( Message::class, $this->callback);
        $this->assertIsString($id2);
    }

    public function testItCanGetMessageDispatcher() : void
    {
        $dispatcher = $this->server->dispatcher();

        $this->assertInstanceOf( MessageDispatcher::class, $dispatcher );
    }


    public function testItCanDispatchMessage() : void
    {
        $request = $this->getMockBuilder( Request::class )
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageListener->expects($this->once())->method('onMessage');

        $this->server->listenAll( $this->messageListener );

        $this->invokeMethod( $this->server,  'dispatch', [
            $request
        ]);

    }

    public function tearDown(): void
    {
        $this->server->stop();
        $this->client->close();
        parent::tearDown();
    }

}
