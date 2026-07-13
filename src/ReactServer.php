<?php

declare(strict_types=1);

namespace Pikart\Goip;

use React\Datagram\Factory as DatagramFactory;
use React\Datagram\Socket;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;

class ReactServer extends Server
{
    /**
     * The Socket instance
     */
    private Socket $socket;

    /**
     * Run server
     */
    public function run(): void
    {
        $loop = $this->createSocket();
        $loop->run();
    }

    /**
     * Create socket server
     */
    private function createSocket(): LoopInterface
    {
        $loop = LoopFactory::create();
        $factory = new DatagramFactory($loop);

        /** @var PromiseInterface<Socket> $server */
        $server = $factory->createServer($this->host . ':' . $this->port);

        $server->then(function (Socket $socket): void {
            $this->socket = $socket;
        });

        $this->socket->on('message', function (string $message, string $address): void {
            $this->onMessage($message, $address);
        });

        return $loop;
    }

    /**
     * Fire when message arrives
     */
    private function onMessage(string $message, string $address): void
    {
        $addressArr = explode(':', $address);

        // Create request
        $request = new Request($message, $addressArr[0], (int) $addressArr[1]);

        // Dispatch message from request
        $this->dispatch($request);
    }

    /**
     * Send message to client
     */
    protected function send(string $message, string $host, int $port): void
    {
        $this->socket->send($message, $host . ':' . $port);
    }
}
