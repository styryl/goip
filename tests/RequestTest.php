<?php

namespace Pikart\Goip\Tests;

use Pikart\Goip\Request;

class RequestTest extends TestCase
{
    private Request $request;

    public function setUp(): void
    {
        $this->request = new Request($this->sampleBuffers('req'), '0.0.0.0', 0);
    }

    public function testItCanReturnHost(): void
    {
        $this->assertSame('0.0.0.0', $this->request->host());
    }

    public function testItCanReturnPort(): void
    {
        $this->assertSame(0, $this->request->port());
    }

    public function testItCanReturnBuffer(): void
    {
        $this->assertSame($this->sampleBuffers('req'), $this->request->buffer());
    }

    public function testItCanParseBufferFromString(): void
    {
        $this->assertSame('gateway_name', $this->request->all()['id']);
    }

    public function testItShouldReturnTrueIfParsedBufferHasKey(): void
    {
        $this->assertTrue($this->request->has('req'));
    }

    public function testItShouldReturnFalseIfParsedBufferDoesntHasKey(): void
    {
        $this->assertFalse($this->request->has('no_existing_key'));
    }

    public function testItShouldReturnStringValueIfParsedBufferHasAttribute(): void
    {
        $this->assertSame('27', $this->request->get('req'));
    }

    public function testItShouldReturnNullValueIfParsedBufferDoesntHasAttribute(): void
    {
        $this->assertNull($this->request->get('no_existing_key'));
    }

    public function testItKeepsSemicolonInsideReceiveMessage(): void
    {
        $request = new Request(
            'RECEIVE:1;id:1;password:1;srcnum:123;msg:Hi; how are you?',
            '0.0.0.0',
            0
        );

        $this->assertSame('Hi; how are you?', $request->get('msg'));
        $this->assertFalse($request->has(' how are you?'));
    }

    public function testItDoesNotParseReceiveMessageContentAsAttributes(): void
    {
        $request = new Request(
            'RECEIVE:1;id:1;password:1;srcnum:123;msg:Hi;srcnum:fake;id:other',
            '0.0.0.0',
            0
        );

        $this->assertSame('123', $request->get('srcnum'));
        $this->assertSame('1', $request->get('id'));
        $this->assertSame('Hi;srcnum:fake;id:other', $request->get('msg'));
    }
}
