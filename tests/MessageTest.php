<?php

namespace Pikart\Goip\Tests;

use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use Pikart\Goip\Message;
use Pikart\Goip\Request;

#[AllowMockObjectsWithoutExpectations]
class MessageTest extends TestCase
{
    public function testItCanReturnRequest(): void
    {
        $requestMock = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();

        $dummyMessage = new class($requestMock) extends Message {
            public function ack(): ?string
            {

            }
        };

        $this->assertInstanceOf(Request::class, $dummyMessage->request());
    }

    public function testItCanReturnRequestAttributes(): void
    {
        $attributes = [
            'test' => 'test',
        ];

        $requestMock = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $requestMock->expects($this->once())
            ->method('all')
            ->willReturn($attributes);

        $dummyMessage = new class($requestMock) extends Message {
            public function ack(): ?string
            {

            }
        };

        $this->assertEquals($attributes, $dummyMessage->attributes());
    }

    public function testItCanReturnAck(): void
    {
        $requestMock = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dummyMessage = new class($requestMock) extends Message {
            public function ack(): ?string
            {
                return 'ack test';
            }
        };

        $this->assertEquals('ack test', $dummyMessage->ack());
    }

    public function testItCanReturnNullAck(): void
    {
        $requestMock = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dummyMessage = new class($requestMock) extends Message {
            public function ack(): ?string
            {
                return null;
            }
        };

        $this->assertNull($dummyMessage->ack());
    }
}
