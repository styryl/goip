<?php

namespace Pikart\Goip\Tests\Messages;

use Pikart\Goip\Messages\StateMessage;
use Pikart\Goip\Request;
use Pikart\Goip\Tests\TestCase;

class StateMessageTest extends TestCase
{
    private StateMessage $message;
    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new Request( $this->sampleBuffers('state'), '0.0.0.0', 0 );
        $this->message = new StateMessage( $this->request );
    }

    public function testItCanReturnAck() : void
    {
        $this->assertEquals( "STATE ".$this->request->get('state').' OK', $this->message->ack() );
        $this->assertIsInt( $this->message->state() );
    }

    public function testItCanReturnState() : void
    {
        $this->assertEquals( $this->request->get('state'), $this->message->state() );
        $this->assertIsInt( $this->message->state() );
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

    public function testItCanReturngsmRemainState() : void
    {
        $this->assertEquals( $this->request->get('gsm_remain_state'), $this->message->gsmRemainState() );
        $this->assertIsString( $this->message->gsmRemainState() );
    }

}


