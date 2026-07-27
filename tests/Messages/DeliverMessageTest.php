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
        $this->request = new Request($this->sampleBuffers('deliver'), '0.0.0.0', 0);
        $this->message = new DeliverMessage($this->request);
    }

    public function testItCanReturnAck(): void
    {
        $this->assertSame('DELIVER 429 OK', $this->message->ack());
    }

    public function testItCanReturnDeliver(): void
    {
        $this->assertSame(429, $this->message->deliver());
    }

    public function testItCanReturnId(): void
    {
        $this->assertSame('c1', $this->message->id());
    }

    public function testItCanReturnPassword(): void
    {
        $this->assertSame('gateway_password', $this->message->password());
    }

    public function testItCanReturnSmsNo(): void
    {
        $this->assertSame(3, $this->message->smsNo());
    }

    public function testItCanReturnState(): void
    {
        $this->assertSame(0, $this->message->state());
    }

    public function testItCanReturnNum(): void
    {
        $this->assertSame('999999999', $this->message->num());
    }
}
