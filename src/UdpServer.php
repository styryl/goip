<?php

declare(strict_types=1);

namespace Pikart\Goip;

class UdpServer extends Server
{
    private \Socket $socket;

    private int $loopSleep = 5000;

    public function setLoopSleep(int $loopSleep): void
    {
        $this->loopSleep = $loopSleep;
    }

    /**
     * @throws Exceptions\SocketException
     */
    public function run(): void
    {
        $this->createSocket();
        while (! $this->stop) {
            $request = $this->receive();
            if ($request === null) {
                usleep($this->loopSleep);
                continue;
            }

            $this->dispatch($request);
        }
    }

    /**
     * @throws Exceptions\SocketException
     */
    private function receive(): ?Request
    {
        $buffer = '';
        $host = '';
        $port = 0;
        $rcvfrom = socket_recvfrom($this->socket, $buffer, 2048, 0, $host, $port);

        if ($rcvfrom === false) {
            $errorCode = socket_last_error($this->socket);

            if ($errorCode === 11) {
                return null;
            }

            throw new Exceptions\SocketException($this->socketLastError());
        }

        if ($rcvfrom === 0) {
            return null;
        }

        /** @var string $buffer */
        /** @var string $host */
        /** @var int $port */
        return new Request($buffer, $host, $port);
    }

    /**
     * @throws Exceptions\SocketException
     */
    protected function send(string $message, string $host, int $port): void
    {
        $socketSendTo = socket_sendto($this->socket, $message, strlen($message), 0, $host, $port);
        if ($socketSendTo === false) {
            throw new Exceptions\SocketException($this->socketLastError());
        }
    }

    /**
     * @throws Exceptions\SocketException
     */
    private function createSocket(): void
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($socket === false) {
            throw new Exceptions\SocketException($this->socketLastError());
        }

        $this->socket = $socket;

        if (! socket_bind($this->socket, $this->host, $this->port)) {
            throw new Exceptions\SocketException($this->socketLastError());
        }

        socket_set_nonblock($this->socket);
    }

    private function closeSocket(): void
    {
        socket_close($this->socket);
    }

    public function stop(): void
    {
        parent::stop();
        $this->closeSocket();
    }

    private function socketLastError(): string
    {
        return socket_strerror(socket_last_error($this->socket));
    }
}
