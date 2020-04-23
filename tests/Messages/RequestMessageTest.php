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
        $this->request = new Request( $this->sampleBuffers('req'), '0.0.0.0', 0 );
        $this->message = new RequestMessage( $this->request );
    }

    public function testItCanReturnAck() : void
    {
        $this->assertEquals( 'req:'.$this->request->get('req').';status:200;', $this->message->ack() );
        $this->assertIsInt( $this->message->req() );
    }

    public function testItCanReturnReq() : void
    {
        $this->assertEquals( $this->request->get('req'), $this->message->req() );
        $this->assertIsInt( $this->message->req() );
    }

    public function testItCanReturnId() : void
    {
        $this->assertEquals( $this->request->get('id'), $this->message->id() );
        $this->assertIsString( $this->message->id() );
    }

    public function testItCanReturnPassword() : void
    {
        $this->assertEquals( $this->request->get('pass'), $this->message->password() );
        $this->assertIsString( $this->message->password() );
    }

    public function testItCanReturnNum() : void
    {
        $this->assertEquals( $this->request->get('num'), $this->message->num() );
        $this->assertIsString( $this->message->num() );
    }

    public function testItCanReturnSignal() : void
    {
        $this->assertEquals( $this->request->get('signal'), $this->message->signal() );
        $this->assertIsInt( $this->message->signal() );
    }

    public function testItCanReturnGsmStatus() : void
    {
        $this->assertEquals( $this->request->get('gsm_status'), $this->message->gsmStatus() );
        $this->assertIsString( $this->message->gsmStatus() );
    }

    public function testItCanReturnVoipStatus() : void
    {
        $this->assertEquals( $this->request->get('voip_status'), $this->message->voipStatus() );
        $this->assertIsString( $this->message->voipStatus() );
    }

    public function testItCanReturnVoipState() : void
    {
        $this->assertEquals( $this->request->get('voip_state'), $this->message->voipState() );
        $this->assertIsString( $this->message->voipState() );
    }

    public function testItCanReturnRemainTime() : void
    {
        $this->assertEquals( $this->request->get('remain_time'), $this->message->remainTime() );
        $this->assertIsInt( $this->message->remainTime() );
    }

    public function testItCanReturnImei() : void
    {
        $this->assertEquals( $this->request->get('imei'), $this->message->imei() );
        $this->assertIsInt( $this->message->imei() );
    }

    public function testItCanReturnPro() : void
    {
        $this->assertEquals( $this->request->get('pro'), $this->message->pro() );
        $this->assertIsString( $this->message->pro() );
    }

    public function testItCanReturnIdle() : void
    {
        $this->assertEquals( $this->request->get('idle'), $this->message->idle() );
        $this->assertIsInt( $this->message->idle() );
    }

    public function testItCanReturnDisableStatus() : void
    {
        $this->assertEquals( $this->request->get('disable_status'), $this->message->disableStatus() );
        $this->assertIsInt( $this->message->disableStatus() );
    }

    public function testItCanReturnSmsLogin() : void
    {
        $this->assertEquals( $this->request->get('sms_login'), $this->message->smsLogin() );
        $this->assertIsString( $this->message->smsLogin() );
    }

    public function testItCanReturnSmbLogin() : void
    {
        $this->assertEquals( $this->request->get('smb_login'), $this->message->smbLogin() );
        $this->assertIsString( $this->message->smbLogin() );
    }

    public function testItCanReturnCellinfo() : void
    {
        $this->assertEquals( $this->request->get('cellinfo'), $this->message->cellinfo() );
        $this->assertIsString( $this->message->cellinfo() );
    }

    public function testItCanReturnCgatt() : void
    {
        $this->assertEquals( $this->request->get('cgatt'), $this->message->cgatt() );
        $this->assertIsString( $this->message->cgatt() );
    }
}


