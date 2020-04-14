<?php

namespace Pikart\Goip;

class Request
{
    private string $buffer;
    private string $host;
    private int $port;
    private array $attributes;

    public function __construct( string $buffer, string $host, int $port )
    {
        $this->buffer = $buffer;
        $this->host = $host;
        $this->port = $port;
        $this->attributes = $this->parse( $this->buffer );
    }

    public function host() : string
    {
        return $this->host;
    }

    public function port() : int
    {
        return $this->port;
    }

    public function buffer() : string
    {
        return $this->buffer;
    }

    public function all() : array
    {
        return $this->attributes;
    }

    public function has( string $key ) : bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function get( string $key ) : ? string
    {
        return $this->has($key) ? $this->attributes[ $key ] : null;
    }

    private function parse( string $buffer ) : array
    {
        $arr = explode(';', $buffer);
        $data = [];
        foreach ( $arr as $value )
        {
            $parts = explode(':', $value);
            $key = array_shift($parts);
            $val = implode(':', $parts);

            if( strlen($key) === 0)
            {
                continue;
            }

            $data[ mb_strtolower( $key ) ] = $val;
        }

        return $data;
    }
}
