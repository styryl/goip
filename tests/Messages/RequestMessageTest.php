<?php

namespace Pikart\Goip\Tests\Messages;

use Pikart\Goip\Messages\RequestMessage;
use Pikart\Goip\Request;
use Pikart\Goip\Tests\TestCase;

class RequestMessageTest extends TestCase
{
    private RequestMessage $message;

    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new Request($this->sampleBuffers('req'), '0.0.0.0', 0);
        $this->message = new RequestMessage($this->request);
    }

    public function testItCanReturnAck(): void
    {
        $this->assertSame('reg:27;status:0;', $this->message->ack());
    }

    public function testItCanReturnReq(): void
    {
        $this->assertSame(27, $this->message->req());
    }

    public function testItCanReturnId(): void
    {
        $this->assertSame('gateway_name', $this->message->id());
    }

    public function testItCanReturnPassword(): void
    {
        $this->assertSame('gateway_password', $this->message->password());
    }

    public function testItCanReturnNum(): void
    {
        $this->assertSame('', $this->message->num());
    }

    public function testItCanReturnSignal(): void
    {
        $this->assertSame(29, $this->message->signal());
    }

    public function testItCanReturnGsmStatus(): void
    {
        $this->assertSame('LOGIN', $this->message->gsmStatus());
    }

    public function testItCanReturnVoipStatus(): void
    {
        $this->assertSame('LOGOUT', $this->message->voipStatus());
    }

    public function testItCanReturnVoipState(): void
    {
        $this->assertSame('IDLE', $this->message->voipState());
    }

    public function testItCanReturnRemainTime(): void
    {
        $this->assertSame(-1, $this->message->remainTime());
    }

    public function testItCanReturnImei(): void
    {
        $this->assertSame(867495023958030, $this->message->imei());
    }

    public function testItCanReturnPro(): void
    {
        $this->assertSame('POL', $this->message->pro());
    }

    public function testItCanReturnIdle(): void
    {
        $this->assertSame(11, $this->message->idle());
    }

    public function testItCanReturnDisableStatus(): void
    {
        $this->assertSame(0, $this->message->disableStatus());
    }

    public function testItCanReturnSmsLogin(): void
    {
        $this->assertSame('N', $this->message->smsLogin());
    }

    public function testItCanReturnSmbLogin(): void
    {
        $this->assertSame('', $this->message->smbLogin());
    }

    public function testItCanReturnCellinfo(): void
    {
        $this->assertSame('LAC:7E5,CELL ID:5E5B', $this->message->cellinfo());
    }

    public function testItCanReturnCgatt(): void
    {
        $this->assertSame('Y', $this->message->cgatt());
    }
}
