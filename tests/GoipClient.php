<?php


namespace Pikart\Goip\Tests;


class GoipClient
{
    private $socket;
    private string $host = '127.0.0.1';
    private int $port = 333;

    public function __construct( string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;

        $this->socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );

        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, [
            'sec' => 1,
            'usec' => 0
        ]);
    }

    public function sendTo( string $message, ?string $host = null, ?int $port = null ) : int
    {
        return socket_sendto($this->socket, $message, strlen($message), 0, $host ?? $this->host, $port ?? $this->port );
    }

    public function receiveFrom() : string
    {
        socket_recvfrom( $this->socket, $buffer, 2048, 0, $fromip, $fromport );
        return $buffer;
    }

    public function close() : void
    {
        socket_close( $this->socket );
    }

}
