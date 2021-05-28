<?php

declare(strict_types=1);

namespace Pikart\Goip\Sms;

use Pikart\Goip\Exceptions\TimeoutException;
use Pikart\Goip\Exceptions\SocketException;
use Pikart\Goip\Exceptions\GoipException;
use Pikart\Goip\Contracts\Sms;

/**
 * @package Pikart\Goip\Sms
 */
class SocketSms implements Sms
{
    /**
     * Goip host
     *
     * @var string
     */
    private string $host;

    /**
     * Goip port number
     *
     * @var int
     */
    private int $port;

    /**
     * Unique session id
     *
     * @var string
     */
    private string $uniqueSessionIdentifier;

    /**
     * Goip password
     *
     * @var string
     */
    private string $password;

    /**
     * Connection options
     *
     * @var mixed[]
     */
    private array $options = [
        'timeout' => 5
    ];

    /**
     * Socket resource
     *
     * @var resource
     */
    private $socket;

    /**
     * SocketSms constructor.
     *
     * @param string $host Goip host
     * @param int $port Goip port
     * @param string $uniqueSessionIdentifier Unique sending session id
     * @param string $password Goip password
     * @param mixed[]|null $options
     * @throws SocketException
     */
    public function __construct(
        string $host,
        int $port,
        string $uniqueSessionIdentifier,
        string $password,
        ?array $options = null
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->uniqueSessionIdentifier = $uniqueSessionIdentifier;
        $this->password = $password;

        if (!is_null($options)) {
            $this->options = array_merge($this->options, $options);
        }

        $this->createSocket();
        $this->setSocketOptions();
    }

    /**
     * Send sms
     *
     * @param string $number Phone number
     * @param string $message Text message
     * @return mixed[] Response from goip
     * @throws GoipException
     * @throws SocketException
     * @throws TimeoutException
     */
    public function send(string $number, string $message): array
    {
        $this->sendBulkSmsRequest($message);
        $this->waitForResponse('sendBulkSmsRequest', 'PASSWORD');
        $this->sendAuthRequest();
        $this->waitForResponse('sendAuthRequest', 'SEND');
        $this->sendNumberRequest($number);
        $response = $this->waitForResponse('sendNumberRequest', 'OK');
        $this->sendEndRequest();
        $this->waitForResponse('sendEndRequest', 'DONE');

        $arr = explode(' ', str_replace(array("\r", "\n"), '', $response));

        return [
            'sendid' => $arr[1], // bulk SMS session identifier
            'telid' => $arr[2], // Integer, unique sequence number in SubmitNumberRequest.
            'sms_no' => $arr[3], // number count of SMS sending in GoIP
            'raw' => $response
        ];
    }

    /**
     * First step of sms sending
     *
     * @param string $message
     */
    protected function sendBulkSmsRequest(string $message): void
    {
        //GOIP message max length is 3000 bytes
        $cutmessage = mb_strcut($message, 0, 3000);
        $message = "MSG " . $this->uniqueSessionIdentifier . " " . strlen($cutmessage) . " " . $cutmessage . "\n";
        $this->sendRequest($message);
    }

    /**
     * Second step of sms sending
     */
    protected function sendAuthRequest(): void
    {
        $message = "PASSWORD " . $this->uniqueSessionIdentifier . " " . $this->password;
        $this->sendRequest($message);
    }

    /**
     * Third step of sms sending
     *
     * @param string $number
     */
    protected function sendNumberRequest(string $number): void
    {
        $message = "SEND " . $this->uniqueSessionIdentifier . " 1 " . $number;
        $this->sendRequest($message);
    }

    /**
     * Last step of sms sending
     */
    protected function sendEndRequest(): void
    {
        $message = "DONE " . $this->uniqueSessionIdentifier;
        $this->sendRequest($message);
    }

    /**
     * Create php socket
     * @throws SocketException
     */
    private function createSocket(): void
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($socket === false) {
            throw new SocketException($this->socketLastError());
        }

        $this->socket = $socket;
    }

    /**
     * Set socket options
     * @throws SocketException
     */
    private function setSocketOptions(): void
    {
        $socketSetOptions = socket_set_option(
            $this->socket,
            SOL_SOCKET,
            SO_RCVTIMEO,
            [
                'sec' => $this->options['timeout'],
                'usec' => 0
            ]
        );

        if (!$socketSetOptions) {
            throw new SocketException($this->socketLastError());
        }
    }

    /**
     * Get last socket error
     *
     * @return string
     */
    private function socketLastError(): string
    {
        return socket_strerror(socket_last_error($this->socket));
    }

    /**
     * Close socket connection
     */
    private function close(): void
    {
        socket_close($this->socket);
    }

    /**
     * Send request to goip
     *
     * @param string $message
     * @throws SocketException
     */
    private function sendRequest(string $message): void
    {
        $socketSendTo = socket_sendto($this->socket, $message, strlen($message), 0, $this->host, $this->port);
        if ($socketSendTo === false) {
            throw new SocketException($this->socketLastError());
        }
    }

    /**
     * Wait for response from goip
     *
     * @param string $request Request info step
     * @param string $response Expected response
     * @return string Return response from Goip if match with expected response
     * @throws GoipException
     * @throws SocketException
     * @throws TimeoutException
     */
    protected function waitForResponse(string $request, string $response): string
    {
        for ($i = 1; $i <= 30; $i++) {
            $socketRecvFrom = socket_recvfrom($this->socket, $buffer, 2048, 0, $fromip, $fromport);
            if ($socketRecvFrom === false) {
                throw new SocketException($this->socketLastError());
            }

            if ($this->isErrorResponse($buffer)) {
                throw new GoipException('Error in ' . $request . ' request: ' . $buffer);
            } elseif ($this->isSuccessResponse($buffer, $response)) {
                return $buffer;
            }
        }

        throw new TimeoutException('Timeout on request: ' . $request);
    }

    /**
     * @param string $buffer
     * @return bool
     */
    private function isErrorResponse(string $buffer): bool
    {
        $expectedErrorMessage = sprintf('ERROR %s', $this->uniqueSessionIdentifier);
        return substr($buffer, 0, (6 + strlen($this->uniqueSessionIdentifier))) === $expectedErrorMessage;
    }

    /**
     * @param string $buffer
     * @param string $response
     * @return bool
     */
    private function isSuccessResponse(string $buffer, string $response): bool
    {
        $expectedSuccessMessage = sprintf('%s %s', $response, $this->uniqueSessionIdentifier);
        $responseLength = strlen($response);
        $uniqueSessionIdentifierLength = strlen($this->uniqueSessionIdentifier);
        $length = 1 + $responseLength + $uniqueSessionIdentifierLength;
        return substr($buffer, 0, $length) === $expectedSuccessMessage;
    }
}
