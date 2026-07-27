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
        $this->request = new Request($this->sampleBuffers('state'), '0.0.0.0', 0);
        $this->message = new StateMessage($this->request);
    }

    public function testItCanReturnAck(): void
    {
        $this->assertSame('STATE 446 OK', $this->message->ack());
    }

    public function testItCanReturnState(): void
    {
        $this->assertSame(446, $this->message->state());
    }

    public function testItCanReturnId(): void
    {
        $this->assertSame('c1', $this->message->id());
    }

    public function testItCanReturnPassword(): void
    {
        $this->assertSame('gateway_password', $this->message->password());
    }

    public function testItCanReturngsmRemainState(): void
    {
        $this->assertSame('DIALING:999999999', $this->message->gsmRemainState());
    }
}
