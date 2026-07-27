<?php

declare(strict_types=1);

namespace Pikart\Goip;

use React\Datagram\Factory as DatagramFactory;
use React\Datagram\Socket;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;

class ReactServer extends Server
{
    private Socket $socket;

    public function run(): void
    {
        $loop = $this->createSocket();
        $loop->run();
    }

    private function createSocket(): LoopInterface
    {
        $loop = Loop::get();
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

    private function onMessage(string $message, string $address): void
    {
        $addressArr = explode(':', $address);
        $request = new Request($message, $addressArr[0], (int) $addressArr[1]);
        $this->dispatch($request);
    }

    protected function send(string $message, string $host, int $port): void
    {
        $this->socket->send($message, $host . ':' . $port);
    }
}
