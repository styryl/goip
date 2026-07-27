<?php

namespace Pikart\Goip\Tests\Messages;

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
        $this->request = new Request($this->sampleBuffers('receive'), '0.0.0.0', 0);
        $this->message = new ReceiveMessage($this->request);
    }

    public function testItCanReturnAck(): void
    {
        $this->assertSame('RECEIVE 441 OK', $this->message->ack());
    }

    public function testItCanReturnReceive(): void
    {
        $this->assertSame(441, $this->message->receive());
    }

    public function testItCanReturnId(): void
    {
        $this->assertSame('c1', $this->message->id());
    }

    public function testItCanReturnPassword(): void
    {
        $this->assertSame('gateway_password', $this->message->password());
    }

    public function testItCanReturnSrcnum(): void
    {
        $this->assertSame(100, $this->message->srcnum());
    }

    public function testItCanReturnMsg(): void
    {
        $this->assertSame('This is sample sms message', $this->message->msg());
    }
}
