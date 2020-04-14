<?php

namespace Pikart\Goip\Tests\Sms;
use Pikart\Goip\Sms\HttpSms;
use Pikart\Goip\Tests\TestCase;

class HttpSmsTest extends TestCase
{

    public function setUp(): void
    {

    }

    public function tearDown(): void
    {

    }

    public function testItCanSendSms() : void
    {
        // http://192.168.2.190/default/en_US/send.html?u=admin&p=admin1&l=1&n=10086&m=test

        // default/en_US/send.html?u=admin&p=admin&l=1&n=695772577&m=test message

//u=admin means username=admin
//p=admin1 means password=admin1
//l=1 means using GSM Channel/Line 1 to send the message
//n=10086 means the SMS recipient number is 10086
//m=test means the message content is “test”
//Here are the two possible responses from the HTTP Send command.

        $sms = new HttpSms('http://192.168.0.11', '1', 'admin', 'admin');
        $response = $sms->send('999999999', 'teest message');
        $this->assertEquals([], $response);

    }

}


