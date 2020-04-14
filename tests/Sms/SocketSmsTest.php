<?php

namespace Pikart\Goip\Tests\Sms;
use Pikart\Goip\Exceptions\GoipException;
use Pikart\Goip\Exceptions\SocketException;
use Pikart\Goip\Sms\SocketSms;
use Pikart\Goip\Tests\GoipServer;
use Pikart\Goip\Tests\TestCase;

class SocketSmsTest extends TestCase
{
    private GoipServer $goipServer;

    public function setUp(): void
    {
       $this->goipServer = new GoipServer('127.0.0.1', 333);
    }

    public function tearDown(): void
    {
        $this->goipServer->close();
    }

    public function testItCanSendBulkSmsRequest() : void
    {
        $sms = new class('127.0.0.1', 333, 'id123', 'password123') extends SocketSms {
            public function sendBulkSmsRequest( string $message ): void
            {
                parent::sendBulkSmsRequest( $message );
            }

            public function waitForResponse(string $request, string $response): string
            {
                return parent::waitForResponse($request, $response);
            }
        };

        // BulkSms
        $sms->sendBulkSmsRequest("test message");
        $this->goipServer->receiveFrom();
        $response = $sms->waitForResponse('BulkSms', 'PASSWORD');
        $this->assertEquals('PASSWORD id123'.PHP_EOL, $response);
    }

    public function testItCanSendAuthRequest() : void
    {
        $sms = new class('127.0.0.1', 333, 'id123', 'password123') extends SocketSms {
            public function sendAuthRequest(): void
            {
                parent::sendAuthRequest();
            }

            public function waitForResponse(string $request, string $response): string
            {
                return parent::waitForResponse($request, $response);
            }
        };

        // AuthenticationRequest
        $sms->sendAuthRequest();
        $this->goipServer->receiveFrom();
        $response = $sms->waitForResponse('Auth', 'SEND');
        $this->assertEquals('SEND id123'.PHP_EOL, $response);
    }

    public function testItCanSendNumberRequest() : void
    {
        $sms = new class('127.0.0.1', 333, 'id123', 'password123') extends SocketSms {
            public function sendNumberRequest( string $number ): void
            {
                parent::sendNumberRequest( $number );
            }

            public function waitForResponse(string $request, string $response): string
            {
                return parent::waitForResponse($request, $response);
            }
        };

        // NumberRequest
        $sms->sendNumberRequest('999999999');
        $this->goipServer->receiveFrom();
        $response = $sms->waitForResponse('SendNumber', 'OK');
        $this->assertEquals('OK id123 1 123'.PHP_EOL, $response);
    }

    public function testItCanSendEndRequest() : void
    {
        $sms = new class('127.0.0.1', 333, 'id123', 'password123') extends SocketSms {
            public function sendEndRequest(): void
            {
                parent::sendEndRequest();
            }

            public function waitForResponse(string $request, string $response): string
            {
                return parent::waitForResponse($request, $response);
            }
        };

        // AuthenticationRequest
        $sms->sendEndRequest();
        $this->goipServer->receiveFrom();
        $response = $sms->waitForResponse('End', 'DONE');
        $this->assertEquals('DONE id123', $response);
    }

    public function testItShouldThrowSocketExceptionWhenGoipNotResponse() : void
    {
        $this->expectException( SocketException::class );

        $sms = new class('127.0.0.1', 333, 'id123', 'password123', [
            'timeout' => 1
        ]) extends SocketSms {
            public function sendBulkSmsRequest( string $message ): void
            {
                parent::sendBulkSmsRequest( $message );
            }

            public function waitForResponse(string $request, string $response): string
            {
                return parent::waitForResponse($request, $response);
            }
        };

        // BulkSms
        $sms->sendBulkSmsRequest("test message");
        $sms->waitForResponse('BulkSms', 'PASSWORD');
    }

    public function testItShouldThrowGoipExceptionWhenGoipResponseWithError() : void
    {
        $this->expectException( GoipException::class );

        $sms = new class('127.0.0.1', 333, 'id123', 'password123', [
            'timeout' => 1
        ]) extends SocketSms {
            public function sendBulkSmsRequest( string $message ): void
            {
                parent::sendBulkSmsRequest( $message );
            }

            public function waitForResponse(string $request, string $response): string
            {
                return parent::waitForResponse($request, $response);
            }
        };

        // BulkSms
        $sms->sendBulkSmsRequest("test message");
        $this->goipServer->receiveFrom(true);
        $response = $sms->waitForResponse('BulkSms', 'PASSWORD');
    }


}


