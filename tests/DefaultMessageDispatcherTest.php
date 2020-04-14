<?php
namespace Pikart\Goip\Tests;

use Pikart\Goip\Contracts\MessageListener;
use Pikart\Goip\Exceptions\MessageDispatcherException;
use Pikart\Goip\Message;
use Pikart\Goip\DefaultMessageDispatcher;

final class DefaultMessageDispatcherTest extends TestCase
{
    private DefaultMessageDispatcher $messageDispatcher;
    private MessageListener $messageListener;
    private object $callback;

    public function setUp(): void
    {
        parent::setUp();
        $this->messageDispatcher = new DefaultMessageDispatcher();

        $this->messageListener = $this->getMockBuilder( MessageListener::class )
            ->disableOriginalConstructor()
            ->onlyMethods(['onMessage'])
            ->getMock();

        $this->callback = function ( Message $message ){};
    }

    public function testItCanRegisterListenerForAllMessages() : void
    {
        $id = $this->messageDispatcher->listenAll( $this->messageListener );
        $this->assertIsString($id);

        $id2 = $this->messageDispatcher->listenAll( $this->callback );
        $this->assertIsString($id2);
    }

    public function testItCanRegisterListenerForConcreteMessage() : void
    {
        $id = $this->messageDispatcher->listen(Message::class, $this->messageListener);
        $this->assertIsString($id);

        $id2 = $this->messageDispatcher->listen(Message::class, $this->callback);
        $this->assertIsString($id2);
    }

    public function testItShouldThrowExceptionIfListenerIsNotCallableOrNotImplementMessageListenerContract() : void
    {
        $this->expectException( \InvalidArgumentException::class );
        $this->messageDispatcher->listen(Message::class, new class {});
    }

    public function testItReturnAllListeners() : void
    {
        $this->assertIsArray($this->messageDispatcher->listeners());
    }

    public function testItRemovesAllListeners() : void
    {
        $this->messageDispatcher->removeAll();
        $this->assertEmpty( $this->messageDispatcher->listeners() );
    }

    public function testItRemovesAllListenersByType() : void
    {
        $messageListenerId = $this->messageDispatcher->listenAll( $this->messageListener );
        $callbackId = $this->messageDispatcher->listen( 'test_type', $this->callback );


        $this->messageDispatcher->removeAll( 'test_type' );

        $this->assertEquals( $this->messageListener, $this->messageDispatcher->get( $messageListenerId ) );
        $this->assertNull( $this->messageDispatcher->get( $callbackId ) );
    }

    public function testItCanRemoveListenerById() : void
    {
        $messageListenerId = $this->messageDispatcher->listenAll( $this->messageListener );
        $callbackId = $this->messageDispatcher->listen( Message::class, $this->callback );

        $this->assertEquals( $this->messageListener, $this->messageDispatcher->get( $messageListenerId ) );
        $this->assertEquals( $this->callback, $this->messageDispatcher->get( $callbackId ) );

        $this->messageDispatcher->remove( $messageListenerId );
        $this->messageDispatcher->remove( $callbackId );

        $this->assertNull( $this->messageDispatcher->get( $messageListenerId ) );
        $this->assertNull( $this->messageDispatcher->get( $callbackId ) );
    }

    public function testItGetListenerById() : void
    {
        $messageListenerId = $this->messageDispatcher->listenAll( $this->messageListener );
        $callbackId = $this->messageDispatcher->listen( Message::class, $this->callback );

        $this->assertEquals( $this->messageListener, $this->messageDispatcher->get( $messageListenerId ) );
        $this->assertEquals( $this->callback, $this->messageDispatcher->get( $callbackId ) );
    }

    public function testItCanDispatchMessage() : void
    {
        $message = $this->getMockBuilder( Message::class )
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->messageListener->expects($this->once())->method('onMessage');

        $this->messageDispatcher->listenAll( $this->messageListener );

        $callbackReturn = false;
        $this->messageDispatcher->listen( Message::class, function ( Message $message ) use (&$callbackReturn) {
            $callbackReturn = true;
        });

        $this->messageDispatcher->dispatch( $message );

        $this->assertTrue( $callbackReturn );

    }




}
