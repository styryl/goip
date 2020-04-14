<?php declare(strict_types=1);

namespace Pikart\Goip\Sms;
use GuzzleHttp\Client;
use Pikart\Goip\Contracts\Sms;
use Pikart\Goip\Exceptions\GoipException;

class HttpSms implements Sms
{
    private string $host;
    private int $line;
    private string $login;
    private string $password;
    private Client $guzzleClient;
    private int $guzzleTimeout = 3;
    private array $guzzleConfig = [];
    private bool $waitForSend = true;
    private int $statusCheckTries = 10;
    private string $sendDir = '/default/en_US/send.html';
    private string $statusDir = '/default/en_US/send_status.xml';

    const STATUS_SENDING = 'sending';
    const STATUS_SEND = 'send';

    public function __construct(string $host, int $line, string $login, string $password)
    {
        $this->host = $host;
        $this->line = $line;
        $this->login = $login;
        $this->password = $password;
    }

    public function send(string $number, string $message): array
    {
        $response = $this->parse( $this->makeRequest($this->prepareAddress($this->sendDir), [
            'u' => $this->login,
            'p' => $this->password,
            'l' => $this->line,
            'n' => $number,
            'm' => $message
        ]) );

        if( $this->waitForSend && !$this->isSend( $response['id'] ) )
        {
            // If sms was not send but is in send queue in qoip
            return $response;
        }

        $response['status'] = HttpSms::STATUS_SEND;

        return $response;
    }

    public function isSend( string $id ) : bool
    {
        for ( $i = 0; $i <= $this->statusCheckTries; $i++ )
        {
            try
            {
                $parsed = $this->parseStatusXml( $this->makeRequest($this->prepareAddress($this->statusDir), [
                    'u' => $this->login,
                    'p' => $this->password,
                ]) );

                if(
                    $parsed['id'] === $id &&
                    strtolower( $parsed['status']  ) === 'done' &&
                    empty( $parsed['error'] ) )
                {
                    return true;
                }

                sleep(1);
            }
            catch ( \Exception $exception )
            {
                continue;
            }
        }

        return false;
    }

    public function setStatusCheckTries( int $tries ) : void
    {
        $this->statusCheckTries = $tries;
    }

    public function setWaitForSend( bool $wait ) : void
    {
        $this->waitForSend = $wait;
    }

    protected function resolveGuzzleClient() : Client
    {
        if( isset( $this->guzzleClient ) )
        {
            return $this->guzzleClient;
        };

        return $this->guzzleClient = new Client( $this->guzzleConfig );
    }

    public function setGuzzleConfig( array $config ) : void
    {
        $this->guzzleConfig = $config;
    }

    public function setGuzzleTimeout( int $timeout ) : void
    {
        $this->guzzleTimeout = $timeout;
    }

    protected function parse( string $response ) : array
    {
        $response = trim( $response );
        if(
            strpos( strtolower( $response ), 'error' ) !== false ||
            strpos( strtolower( $response ), 'sending' ) === false
        )
        {
            throw new GoipException($response);
        }

        $responseArr = explode(' ', $response );
        $id = end($responseArr);

        if( strpos( strtolower($id), 'id' ) === false )
        {
            throw new GoipException('Sms id not found in response');
        }

        $id = explode( ':', $id )[1];

        return [
            'id'     => $id,
            'raw'    => $response,
            'status' => HttpSms::STATUS_SENDING
        ];
    }

    protected function parseStatusXml( string $response ) : array
    {
        $xml = new \SimpleXMLElement( $response );

        $idNode = 'id'.$this->line;
        $statusNode = 'status'.$this->line;
        $errorNode = 'error'.$this->line;

        return [
            'id'     => (string) $xml->children()->{ $idNode },
            'status' => (string) $xml->children()->{ $statusNode },
            'error'  => (string) $xml->children()->{ $errorNode },
        ];
    }

    protected function prepareAddress( string $dir ) : string
    {
        $host = $this->host;

        if(substr($this->host, -1) == '/')
        {
            $host = substr($this->host, 0, -1);
        }

        return $host . $dir;
    }

    protected function makeRequest( string $address, array $query) : string
    {
        $response = $this->resolveGuzzleClient()->request( 'GET', $address, [
            'timeout' => $this->guzzleTimeout,
            'allow_redirects' => false,
            'query' => $query
        ]);

        if( $response->getStatusCode() !== 200 )
        {
            throw new GoipException('Goip does not respond correctly on address: '.$address.', please check the host, status: '.$response->getStatusCode());
        }

        return $response->getBody()->getContents();
    }
}
