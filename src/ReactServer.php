<?php

namespace Pikart\Goip;

use React\EventLoop\Factory as LoopFactory;
use React\Datagram\Factory as DatagramFactory;
use React\Datagram\Socket;
use React\EventLoop\LoopInterface;

class ReactServer extends Server
{
    private Socket $socket;

    public function run(): void
    {
        $loop = $this->createSocket();
        $loop->run();
    }

    private function createSocket() : LoopInterface
    {
        $loop = LoopFactory::create();
        $factory = new DatagramFactory($loop);

        $factory->createServer( $this->host.':'.$this->port )->then( function ( Socket $socket ) : void {
            $this->socket = $socket;
        });

        $this->socket->on('message', function($message, $address) : void {
            $this->onMessage( $message, $address );
        });

        return $loop;
    }

    private function onMessage( string $message, string $address ) : void
    {
        $addressArr = explode(':', $address);

        // Create request
        $request = new Request( $message, $addressArr[0], $addressArr[1] );

        // Dispatch message from request
        $this->dispatch( $request );
    }

    protected function send(string $message, string $host, int $port ): void
    {
        $this->socket->send( $message, $host.':'.$port );
    }


}
