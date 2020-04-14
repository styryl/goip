<?php

namespace Pikart\Goip\Tests\Sms;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Pikart\Goip\Exceptions\GoipException;
use Pikart\Goip\Sms\HttpSms;
use Pikart\Goip\Tests\TestCase;

class HttpSmsTest extends TestCase
{

    private HttpSms $sms;
    private string $host = 'http://192.168.0.11';
    protected int $line = 1;

    public function setUp(): void
    {
        $this->sms = new class($this->host, $this->line, 'admin', 'admin') extends HttpSms {
            public function parse(string $response): array
            {
                return parent::parse($response);
            }

            public function prepareAddress($type): string
            {
                return parent::prepareAddress($type);
            }

            public function parseStatusXml(string $response): array
            {
                return parent::parseStatusXml($response);
            }

        };
    }

    public function testItCanParseResponse() : void
    {
        $exampleResponse = 'Sending,L1 Send SMS to:999999999; ID:00000e7c';
        $response = $this->sms->parse( $exampleResponse );

        $this->assertEquals([
            'raw' => $exampleResponse,
            'id'  => '00000e7c',
            'status' => HttpSms::STATUS_SENDING
        ], $response);
    }

    public function testItShouldThrowExceptionIfGoipResponseWithError() : void
    {
        $this->expectException( GoipException::class );

        $exampleResponse = 'ERROR,L2 GSM logout';
        $this->sms->parse( $exampleResponse );
    }

    public function testItShouldThrowExceptionIfHostIsWrongAfterTimeout() : void
    {
        $this->expectException(ConnectException::class);
        $sms = new HttpSms('http://192.168.0.12', '1', 'admin', 'admin');
        $sms->setWaitForSend(false);
        $sms->setGuzzleTimeout(1);
        $response = $sms->send('999999999', 'teest message');
    }

    public function testItCanPrepareAddress() : void
    {
        $this->assertEquals( $this->host.'/default/en_US/send.html', $this->sms->prepareAddress('/default/en_US/send.html') );
        $this->assertEquals( $this->host.'/default/en_US/send_status.xml', $this->sms->prepareAddress('/default/en_US/send_status.xml') );
    }

    public function testItCanCheckStatusBySendId() : void
    {
        $mock = new MockHandler([
            new Response(200, [], $this->exampleStatusList())
        ]);

        $this->sms->setGuzzleConfig([
            'handler' => HandlerStack::create($mock)
        ]);

        $this->assertTrue( $this->sms->isSend('00000e7c') );
        $this->assertFalse( $this->sms->isSend('00000e72') );

    }

    public function testItCanParseXmlStatusList() : void
    {
        $parsed = $this->sms->parseStatusXml( $this->exampleStatusList() );

        $this->assertEquals([
            'id' => '00000e7c',
            'status' => 'DONE',
            'error'  => ''
        ], $parsed);
    }

    public function testItCanSendSms() : void
    {
        $mock = new MockHandler([
            new Response(200, [], 'Sending,L1 Send SMS to:999999999; ID:00000e7c'),
            new Response(200, [], $this->exampleStatusList())
        ]);

        $this->sms->setGuzzleConfig([
            'handler' => HandlerStack::create($mock)
        ]);

        $response = $this->sms->send('999999999', 'test message');

        $this->assertEquals([
            'id' => '00000e7c',
            'raw' => 'Sending,L1 Send SMS to:999999999; ID:00000e7c',
            'status' => HttpSms::STATUS_SEND
        ], $response);
    }

    private function exampleStatusList( string $error = '' ) : string
    {
        return '<send-sms-status>
                <id1>00000e7c</id1>
                <status1>DONE</status1>
                <error1>'.$error.'</error1>
                <id2/>
                <status2/>
                <error2/>
                <id3/>
                <status3/>
                <error3/>
                <id4/>
                <status4/>
                <error4/>
                <id5/>
                <status5/>
                <error5/>
                <id6/>
                <status6/>
                <error6/>
                <id7/>
                <status7/>
                <error7/>
                <id8/>
                <status8/>
                <error8/>
                </send-sms-status>';
    }
}


