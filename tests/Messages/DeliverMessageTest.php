<?php

namespace Pikart\Goip\Tests\Messages;

use Pikart\Goip\Messages\DeliverMessage;
use Pikart\Goip\Request;
use Pikart\Goip\Tests\TestCase;

class DeliverMessageTest extends TestCase
{
    private DeliverMessage $message;
    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new Request( $this->sampleBuffers('deliver'), '0.0.0.0', 0 );
        $this->message = new DeliverMessage( $this->request );
    }

    public function testItCanReturnAck() : void
    {
        $this->assertEquals( "DELIVER ".$this->request->get('deliver').' OK', $this->message->ack() );
        $this->assertIsInt( $this->message->deliver() );
    }

    public function testItCanReturnDeliver() : void
    {
        $this->assertEquals( $this->request->get('deliver'), $this->message->deliver() );
        $this->assertIsInt( $this->message->deliver() );
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

    public function testItCanReturnSmsNo() : void
    {
        $this->assertEquals( $this->request->get('sms_no'), $this->message->smsNo() );
        $this->assertIsInt( $this->message->smsNo() );
    }

    public function testItCanReturnState() : void
    {
        $this->assertEquals( $this->request->get('state'), $this->message->state() );
        $this->assertIsInt( $this->message->state() );
    }

    public function testItCanReturnNum() : void
    {
        $this->assertEquals( $this->request->get('num'), $this->message->num() );
        $this->assertIsString( $this->message->num() );
    }

}


