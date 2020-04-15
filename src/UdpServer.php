<?php

namespace Pikart\Goip;

use Pikart\Goip\Events\RequestEvent;

class UdpServer extends Server
{
    /**
     * Ther Socket resource
     * @var resource
     */
    private $socket;

    /**
     * Loop sleep interval in milliseconds
     *
     * @var int
     */
    private int $loopSleep = 5000;

    /**
     * Set loop sleep
     *
     * @param int $loopSleep
     */
    public function setLoopSleep( int $loopSleep ) : void
    {
        $this->loopSleep = $loopSleep;
    }

    /**
     * Run server
     *
     * @throws Exceptions\SocketException
     */
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

    /**
     * Read from socket
     *
     * @return Request|null
     * @throws Exceptions\SocketException
     */
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

    /**
     * Send message to client
     *
     * @param string $message
     * @param string $host
     * @param int $port
     * @throws Exceptions\SocketException
     */
    protected function send( string $message, string $host, int $port ) : void
    {
        if( !$return = socket_sendto($this->socket, $message, strlen($message), 0, $host, $port ) )
        {
            throw new Exceptions\SocketException( $this->socketLastError() );
        }
    }

    /**
     * Create socket
     *
     * @throws Exceptions\SocketException
     */
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

    /**
     * Close socket connection
     */
    private function closeSocket() : void
    {
        socket_close($this->socket);
    }

    /**
     * Stop server
     */
    public function stop(): void
    {
        parent::stop();
        $this->closeSocket();
    }

    /**
     * Get last socket error
     *
     * @return string
     */
    private function socketLastError() : string
    {
        return socket_strerror( socket_last_error( $this->socket ) );
    }
}
