<?php

namespace Pikart\Goip\Tests\Messages;

use Pikart\Goip\Messages\ExpiryMessage;
use Pikart\Goip\Request;
use Pikart\Goip\Tests\TestCase;

class ExpiryMessageTest extends TestCase
{
    private ExpiryMessage $message;

    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new Request($this->sampleBuffers('expiry'), '0.0.0.0', 0);
        $this->message = new ExpiryMessage($this->request);
    }

    public function testItCanReturnAck(): void
    {
        $this->assertSame('EXPIRY 1785167081 OK', $this->message->ack());
    }

    public function testItCanReturnExpiry(): void
    {
        $this->assertSame(1785167081, $this->message->expiry());
    }

    public function testItCanReturnExp(): void
    {
        $this->assertSame(0, $this->message->exp());
    }
}
