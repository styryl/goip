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
        $this->request = new Request($this->sampleBuffers('record'), '0.0.0.0', 0);
        $this->message = new RecordMessage($this->request);
    }

    public function testItCanReturnAck(): void
    {
        $this->assertSame('RECORD 445 OK', $this->message->ack());
    }

    public function testItCanReturnRecord(): void
    {
        $this->assertSame(445, $this->message->record());
    }

    public function testItCanReturnId(): void
    {
        $this->assertSame('c1', $this->message->id());
    }

    public function testItCanReturnPassword(): void
    {
        $this->assertSame('gateway_password', $this->message->password());
    }

    public function testItCanReturnDir(): void
    {
        $this->assertSame(2, $this->message->dir());
    }

    public function testItCanReturnNum(): void
    {
        $this->assertSame('999999999', $this->message->num());
    }
}
