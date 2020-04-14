<?php

namespace Pikart\Goip\Tests\Messages;

use Pikart\Goip\Messages\RecordMessage;
use Pikart\Goip\Request;
use Pikart\Goip\Tests\TestCase;

class RecordMessageTest extends TestCase
{
    private RecordMessage $message;
    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new Request( $this->sampleBuffers('record'), '0.0.0.0', 0 );
        $this->message = new RecordMessage( $this->request );
    }

    public function testItCanReturnRecord() : void
    {
        $this->assertEquals( $this->request->get('record'), $this->message->record() );
        $this->assertIsInt( $this->message->record() );
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

    public function testItCanReturnDir() : void
    {
        $this->assertEquals( $this->request->get('dir'), $this->message->dir() );
        $this->assertIsInt( $this->message->dir() );
    }

    public function testItCanReturnNum() : void
    {
        $this->assertEquals( $this->request->get('num'), $this->message->num() );
        $this->assertIsString( $this->message->num() );
    }

}


