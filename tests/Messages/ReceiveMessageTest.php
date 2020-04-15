<?php

namespace Pikart\Goip\Tests\Messages;

use Pikart\Goip\Messages\HangupMessage;
use Pikart\Goip\Messages\ReceiveMessage;
use Pikart\Goip\Request;
use Pikart\Goip\Tests\TestCase;

class ReceiveMessageTest extends TestCase
{
    private ReceiveMessage $message;
    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new Request( $this->sampleBuffers('receive'), '0.0.0.0', 0 );
        $this->message = new ReceiveMessage( $this->request );
    }

    public function testItCanReturnAck() : void
    {
        $this->assertEquals( "RECEIVE ".$this->request->get('receive').' OK', $this->message->ack() );
        $this->assertIsInt( $this->message->receive() );
    }

    public function testItCanReturnReceive() : void
    {
        $this->assertEquals( $this->request->get('receive'), $this->message->receive() );
        $this->assertIsInt( $this->message->receive() );
    }

    public function testItCanReturnId() : void
    {
        $this->assertEquals( $this->request->get('id'), $this->message->id() );
        $this->assertIsString( $this->message->id() );
    }

    public function testItCanReturnPassword() : void
    {
        $this->assertEquals( $this->request->get('password'), $this->message->password() );
        $this->assertIsString( $this->message->password() );
    }

    public function testItCanReturnSrcnum() : void
    {
        $this->assertEquals( $this->request->get('srcnum'), $this->message->srcnum() );
        $this->assertIsInt( $this->message->srcnum() );
    }

    public function testItCanReturnMsg() : void
    {
        $this->assertEquals( $this->request->get('msg'), $this->message->msg() );
        $this->assertIsString( $this->message->msg() );
    }

}


