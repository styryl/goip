<?php

namespace Pikart\Goip\Tests\Messages;

use Pikart\Goip\Messages\HangupMessage;
use Pikart\Goip\Request;
use Pikart\Goip\Tests\TestCase;

class HangupMessageTest extends TestCase
{
    private HangupMessage $message;
    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new Request( $this->sampleBuffers('hangup'), '0.0.0.0', 0 );
        $this->message = new HangupMessage( $this->request );
    }

    public function testItCanReturnHangup() : void
    {
        $this->assertEquals( $this->request->get('hangup'), $this->message->hangup() );
        $this->assertIsInt( $this->message->hangup() );
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

    public function testItCanReturnNum() : void
    {
        $this->assertEquals( $this->request->get('num'), $this->message->num() );
        $this->assertIsString( $this->message->num() );
    }

}


