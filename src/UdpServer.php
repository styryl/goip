<?php

namespace Pikart\Goip;

use Pikart\Goip\Events\RequestEvent;

class UdpServer extends Server
{
    private $socket;
    private int $loopSleep = 5000;

    public function setLoopSleep( int $loopSleep ) : void
    {
        $this->loopSleep = $loopSleep;
    }

    public function run() : void
    {
        $this->createSocket();

        while ( !$this->stop )
        {
            $request = $this->receive();

            if( is_null( $request )  )
            {
                if( !$this->stop )
                {
                    usleep($this->loopSleep);
                }

                continue;
            }

            $this->dispatch( $request );
        }
    }

    private function receive() : ? Request
    {
         $rcvfrom = socket_recvfrom($this->socket, $buffer, 2048, 0, $host, $port);

         // If there was an error
        if( $rcvfrom === false )
        {
            $errorCode = socket_last_error($this->socket);

            if( $errorCode === 11 )
            {
                return  null;
            }

            throw new Exceptions\SocketException( $this->socketLastError() );
        }

        if( $rcvfrom === 0 )
        {
            return null;
        }

        return new Request($buffer,$host,$port);
    }

    protected function send( string $message, string $host, int $port ) : void
    {
        if( !$return = socket_sendto($this->socket, $message, strlen($message), 0, $host, $port ) )
        {
            throw new Exceptions\SocketException( $this->socketLastError() );
        }
    }

    private function createSocket() : void
    {
        if(!$this->socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP ) )
        {
            throw new Exceptions\SocketException( $this->socketLastError() );
        }

        if( !socket_bind( $this->socket, $this->host, $this->port ) )
        {
            throw new Exceptions\SocketException( $this->socketLastError() );
        }

        socket_set_nonblock($this->socket);
    }

    private function closeSocket() : void
    {
        socket_close($this->socket);
    }

    public function stop(): void
    {
        parent::stop();
        $this->closeSocket();
    }

    private function socketLastError() : string
    {
        return socket_strerror( socket_last_error( $this->socket ) );
    }
}
