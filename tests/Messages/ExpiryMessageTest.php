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
        $this->assertEquals('EXPIRY ' . $this->request->get('expiry') . ' OK', $this->message->ack());
        $this->assertIsInt($this->message->expiry());
    }

    public function testItCanReturnExpiry(): void
    {
        $this->assertEquals($this->request->get('expiry'), $this->message->expiry());
        $this->assertIsInt($this->message->expiry());
    }

    public function testItCanReturnExp(): void
    {
        $this->assertEquals($this->request->get('exp'), $this->message->exp());
        $this->assertIsInt($this->message->exp());
    }
}
