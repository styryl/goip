<?php

namespace Pikart\Goip\Tests;

class GoipClient
{
    private \Socket $socket;

    public function __construct(
        private string $host,
        private int $port
    ) {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($socket === false) {
            throw new \RuntimeException(socket_strerror(socket_last_error()));
        }

        $this->socket = $socket;

        $optionSet = socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, [
            'sec' => 1,
            'usec' => 0,
        ]);
        if ($optionSet === false) {
            throw new \RuntimeException(socket_strerror(socket_last_error($this->socket)));
        }
    }

    public function sendTo(string $message, ?string $host = null, ?int $port = null): int
    {
        $sent = socket_sendto($this->socket, $message, strlen($message), 0, $host ?? $this->host, $port ?? $this->port);
        if ($sent === false) {
            throw new \RuntimeException(socket_strerror(socket_last_error($this->socket)));
        }

        return $sent;
    }

    public function receiveFrom(): string
    {
        $buffer = '';
        $fromip = '';
        $fromport = 0;
        $received = socket_recvfrom($this->socket, $buffer, 2048, 0, $fromip, $fromport);
        if ($received === false) {
            throw new \RuntimeException(socket_strerror(socket_last_error($this->socket)));
        }

        return $buffer;
    }

    public function close(): void
    {
        socket_close($this->socket);
    }
}
