<?php

namespace Pikart\Goip\Tests;

use Pikart\Goip\Request;

class RequestTest extends TestCase
{
    private Request $request;

    public function setUp(): void
    {
        $this->request = new Request($this->sampleBuffers('req'),'0.0.0.0', 0);
    }

    public function testItCanReturnHost() : void
    {
        $this->assertIsString( $this->request->host() );
    }

    public function testItCanReturnPort() : void
    {
        $this->assertIsInt( $this->request->port() );
    }

    public function testItCanReturnBuffer() : void
    {
        $this->assertEquals($this->sampleBuffers('req'), $this->request->buffer() );
    }

    public function testItCanParseBufferFromString() : void
    {
        $this->assertIsArray( $this->request->all() );
    }

    public function testItShouldReturnTrueIfParsedBufferHasKey() : void
    {
        $this->assertTrue( $this->request->has('req') );
    }

    public function testItShouldReturnFalseIfParsedBufferDoesntHasKey() : void
    {
        $this->assertFalse( $this->request->has('no_existing_key') );
    }

    public function testItShouldReturnStringValueIfParsedBufferHasAttribute() : void
    {
        $this->assertEquals('27', $this->request->get('req') );
    }

    public function testItShouldReturnNullValueIfParsedBufferDoesntHasAttribute() : void
    {
        $this->assertNull($this->request->get('no_existing_key') );
    }

}
