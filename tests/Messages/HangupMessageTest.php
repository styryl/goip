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
        $this->request = new Request($this->sampleBuffers('hangup_with_cause'), '0.0.0.0', 0);
        $this->message = new HangupMessage($this->request);
    }

    public function testItCanReturnAck(): void
    {
        $this->assertSame('HANGUP 442 OK', $this->message->ack());
    }

    public function testItCanReturnHangup(): void
    {
        $this->assertSame(442, $this->message->hangup());
    }

    public function testItCanReturnId(): void
    {
        $this->assertSame('c1', $this->message->id());
    }

    public function testItCanReturnPassword(): void
    {
        $this->assertSame('gateway_password', $this->message->password());
    }

    public function testItCanReturnNum(): void
    {
        $this->assertSame('', $this->message->num());
    }

    public function testItCanReturnCause(): void
    {
        $this->assertSame(16, $this->message->cause());
    }
}
