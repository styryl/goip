<?php

namespace Pikart\Goip\Tests;

class GoipServer
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

        $bound = socket_bind($this->socket, $this->host, $this->port);
        if ($bound === false) {
            throw new \RuntimeException(socket_strerror(socket_last_error($this->socket)));
        }

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

    public function receiveFrom(bool $sendError = false): string
    {
        $buffer = '';
        $fromip = '';
        $fromport = 0;
        $received = socket_recvfrom($this->socket, $buffer, 2048, 0, $fromip, $fromport);
        if ($received === false) {
            throw new \RuntimeException(socket_strerror(socket_last_error($this->socket)));
        }

        // BulkSMSRequest Format: MSG $sendid $length $msg\n
        if (strpos($buffer, 'MSG') !== false) {
            $arr = explode(' ', $buffer);

            // BulkSMSRequest ERROR $sendid $errormsg\n
            if ($sendError) {
                $this->sendTo('ERROR ' . $arr[1] . ' Error message' . PHP_EOL, $fromip, $fromport);
            } else {
                $this->sendTo('PASSWORD ' . $arr[1] . PHP_EOL, $fromip, $fromport);
            }
        }

        // AuthenticationRequest Format: PASSWORD $sendid $password\n
        if (strpos($buffer, 'PASSWORD') !== false) {
            $arr = explode(' ', $buffer);
            $this->sendTo('SEND ' . $arr[1] . PHP_EOL, $fromip, $fromport);
        }

        // SubmitNumberRequest Format: SEND $sendid $telid $telnum\n
        if (strpos($buffer, 'SEND') !== false) {
            $arr = explode(' ', $buffer);
            $this->sendTo('OK ' . $arr[1] . ' ' . $arr[2] . ' 123' . PHP_EOL, $fromip, $fromport);
        }

        // EndReqeust Format: DONE $sendid\n
        if (strpos($buffer, 'DONE') !== false) {
            $arr = explode(' ', $buffer);
            $this->sendTo('DONE ' . $arr[1], $fromip, $fromport);
        }

        return $buffer;
    }

    public function close(): void
    {
        socket_close($this->socket);
    }
}
