<?php

namespace Pikart\Goip\Tests;

use Pikart\Goip\DefaultMessageFactory;
use Pikart\Goip\Message;
use Pikart\Goip\Messages\DeliverMessage;
use Pikart\Goip\Messages\HangupMessage;
use Pikart\Goip\Messages\NotSupportedMessage;
use Pikart\Goip\Messages\ReceiveMessage;
use Pikart\Goip\Messages\RecordMessage;
use Pikart\Goip\Messages\RequestMessage;
use Pikart\Goip\Messages\StateMessage;
use Pikart\Goip\Request;

class DefaultMessageFactoryTest extends TestCase
{
    private DefaultMessageFactory $factory;

    public function setUp(): void
    {
        $this->factory = new DefaultMessageFactory();
    }

    public function testItCanMakeRequestMessage() : void
    {
        $this->assertInstanceOf( RequestMessage::class, $this->makeMessage('req') );
    }

    public function testItCanMakeStateMessage() : void
    {
        $this->assertInstanceOf( StateMessage::class, $this->makeMessage('state') );
    }

    public function testItCanMakeRecordMessage() : void
    {
        $this->assertInstanceOf( RecordMessage::class, $this->makeMessage('record') );
    }

    public function testItCanMakeHangupMessage() : void
    {
        $this->assertInstanceOf( HangupMessage::class, $this->makeMessage('hangup') );
    }

    public function testItCanMakeReceiveMessage() : void
    {
        $this->assertInstanceOf( ReceiveMessage::class, $this->makeMessage('receive') );
    }

    public function testItCanMakeDeliverMessage() : void
    {
        $this->assertInstanceOf( DeliverMessage::class, $this->makeMessage('deliver') );
    }

    public function testItMakeNotSupportedMessageIfRequestTypeIsNotSupported() : void
    {
        $this->assertInstanceOf( NotSupportedMessage::class, $this->makeMessage('other') );
    }

    private function makeMessage( string $type ) : Message
    {
        return $this->factory->make( new Request( $this->sampleBuffers( $type ), '0.0.0.0', 0 ) );
    }
}
