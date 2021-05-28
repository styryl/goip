<?php

declare(strict_types=1);

namespace Pikart\Goip\Sms;

use Pikart\Goip\Exceptions\GoipException;
use Pikart\Goip\Contracts\Sms;
use GuzzleHttp\Client;

/**
 * @package Pikart\Goip\Sms
 */
class HttpSms implements Sms
{
    /**
     * Goip host
     *
     * @var string
     */
    private string $host;

    /**
     * Goip line number
     *
     * @var int
     */
    private int $line;

    /**
     * Goip login
     *
     * @var string
     */
    private string $login;

    /**
     * Goip password
     *
     * @var string
     */
    private string $password;

    /**
     * Guzzle Client
     *
     * @var Client
     */
    private Client $guzzleClient;

    /**
     * Guzzle timeout
     *
     * @var int
     */
    private int $guzzleTimeout = 3;

    /**
     * Guzzle config
     *
     * @var mixed[]
     */
    private array $guzzleConfig = [];

    /**
     * Wait for status check flag
     *
     * @var bool
     */
    private bool $waitForSend = true;

    /**
     * Number of status check tires
     *
     * @var int
     */
    private int $statusCheckTries = 10;

    /**
     * Url for sms send
     *
     * @var string
     */
    private string $sendDir = '/default/en_US/send.html';

    /**
     * Url for status check
     *
     * @var string
     */
    private string $statusDir = '/default/en_US/send_status.xml';

    /**
     * Status for sms that was not sent, but is in sending state
     */
    public const STATUS_SENDING = 'sending';

    /**
     * Status for sms that was sent
     */
    public const STATUS_SEND = 'send';

    /**
     * HttpSms constructor.
     *
     * @param string $host Goip host for example: http://192.168.0.11
     * @param int $line Goip line number,
     * @param string $login Goip login
     * @param string $password Goip password
     */
    public function __construct(string $host, int $line, string $login, string $password)
    {
        $this->host = $host;
        $this->line = $line;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Send sms
     *
     * @param string $number Phone number
     * @param string $message Text message
     * @return mixed[]
     * @throws GoipException
     */
    public function send(string $number, string $message): array
    {
        $response = $this->parse(
            $this->makeRequest(
                $this->prepareAddress($this->sendDir),
                [
                    'u' => $this->login,
                    'p' => $this->password,
                    'l' => $this->line,
                    'n' => $number,
                    'm' => $message
                ]
            )
        );

        if ($this->waitForSend && !$this->isSend($response['id'])) {
            // If sms was not send but is in send queue in qoip
            return $response;
        }

        $response['status'] = HttpSms::STATUS_SEND;

        return $response;
    }

    /**
     * Check if sms was sent
     *
     * @param string $uniqueSessionIdentifier Goip unique session id
     * @return bool
     */
    public function isSend(string $uniqueSessionIdentifier): bool
    {
        for ($i = 0; $i <= $this->statusCheckTries; $i++) {
            try {
                $parsed = $this->parseStatusXml(
                    $this->makeRequest(
                        $this->prepareAddress($this->statusDir),
                        [
                            'u' => $this->login,
                            'p' => $this->password,
                        ]
                    )
                );

                if (
                    $parsed['id'] === $uniqueSessionIdentifier
                    && strtolower($parsed['status']) === 'done'
                    && isset($parsed['error'])
                ) {
                    return true;
                }

                sleep(1);
            } catch (\Exception $exception) {
                continue;
            }
        }

        return false;
    }

    /**
     * Set loop tries for status checking
     *
     * @param int $tries
     */
    public function setStatusCheckTries(int $tries): void
    {
        $this->statusCheckTries = $tries;
    }

    /**
     * Set if send command must wait for status check
     *
     * @param bool $wait
     */
    public function setWaitForSend(bool $wait): void
    {
        $this->waitForSend = $wait;
    }

    /**
     * Resolve Guzzle Client
     *
     * @return Client
     */
    protected function resolveGuzzleClient(): Client
    {
        if (isset($this->guzzleClient)) {
            return $this->guzzleClient;
        };

        return $this->guzzleClient = new Client($this->guzzleConfig);
    }

    /**
     * Set guzzle client config
     *
     * @param mixed[] $config
     */
    public function setGuzzleConfig(array $config): void
    {
        $this->guzzleConfig = $config;
    }

    /**
     * Set guzzle request timeout
     *
     * @param int $timeout
     */
    public function setGuzzleTimeout(int $timeout): void
    {
        $this->guzzleTimeout = $timeout;
    }

    /**
     * Parse response from goip
     *
     * @param string $response
     * @return mixed[]
     * @throws GoipException
     */
    protected function parse(string $response): array
    {
        $response = trim($response);
        if (
            strpos(strtolower($response), 'error') !== false ||
            strpos(strtolower($response), 'sending') === false
        ) {
            throw new GoipException($response);
        }

        $responseArr = explode(' ', $response);
        $identifier = end($responseArr);

        if (strpos(strtolower($identifier), 'id') === false) {
            throw new GoipException('Sms id not found in response');
        }

        $identifier = explode(':', $identifier)[1];

        return [
            'id' => $identifier,
            'raw' => $response,
            'status' => HttpSms::STATUS_SENDING
        ];
    }

    /**
     * Parse Goip status list
     *
     * @param string $response
     * @return mixed[]
     */
    protected function parseStatusXml(string $response): array
    {
        $xml = new \SimpleXMLElement($response);

        $idNode = 'id' . $this->line;
        $statusNode = 'status' . $this->line;
        $errorNode = 'error' . $this->line;

        return [
            'id' => (string)$xml->children()->{$idNode},
            'status' => (string)$xml->children()->{$statusNode},
            'error' => (string)$xml->children()->{$errorNode},
        ];
    }

    /**
     * Prepare goip command HTTP address
     *
     * @param string $dir
     * @return string
     */
    protected function prepareAddress(string $dir): string
    {
        $host = $this->host;

        if (substr($this->host, -1) == '/') {
            $host = substr($this->host, 0, -1);
        }

        return $host . $dir;
    }

    /**
     * Make request to Goip by guzzle client
     *
     * @param string $address
     * @param mixed[] $query
     * @return string
     * @throws GoipException
     */
    protected function makeRequest(string $address, array $query): string
    {
        $response = $this->resolveGuzzleClient()->request(
            'GET',
            $address,
            [
                'timeout' => $this->guzzleTimeout,
                'allow_redirects' => false,
                'query' => $query
            ]
        );

        if ($response->getStatusCode() !== 200) {
            $exceptionMessage = sprintf(
                'Goip does not respond correctly on address: %s, please check the host, status: %u',
                $address,
                $response->getStatusCode()
            );

            throw new GoipException($exceptionMessage);
        }

        return $response->getBody()->getContents();
    }
}
