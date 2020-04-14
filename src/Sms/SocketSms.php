<?php
namespace Pikart\Goip\Sms;

use Pikart\Goip\Contracts\Sms;
use Pikart\Goip\Exceptions\GoipException;
use Pikart\Goip\Exceptions\SocketException;
use Pikart\Goip\Exceptions\TimeoutException;

class SocketSms implements Sms
{
    private string $host;
    private int $port;
    private string $id;
    private string $password;
    private array $options = [
        'timeout' => 5
    ];
    private $socket;

    public function __construct( string $host, int $port, string $id, string $password, ?array $options = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->id = $id;
        $this->password = $password;

        if( $options )
        {
            array_merge( $this->options, $options );
        }

        $this->createSocket();
        $this->setSocketOptions();
    }

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

        $arr = explode( ' ', str_replace(array("\r", "\n"), '', $response ) );

        return [
            'sendid' => $arr[1], // bulk SMS session identifier
            'telid'  => $arr[2], // Integer, unique sequence number in SubmitNumberRequest.
            'sms_no' => $arr[3], // number count of SMS sending in GoIP
            'raw'    => $response
        ];
    }

    protected function sendBulkSmsRequest( string $message ) : void
    {
        //GOIP message max length is 3000 bytes
        $cutmessage = mb_strcut( $message, 0, 3000);
        $message = "MSG " . $this->id . " " . strlen($cutmessage) . " " . $cutmessage. "\n";
        $this->sendRequest($message);
    }

    protected function sendAuthRequest()  : void
    {
        $message = "PASSWORD " . $this->id . " " . $this->password;
        $this->sendRequest($message);
    }

    protected function sendNumberRequest( string $number ) : void
    {
        $message = "SEND " . $this->id . " 1 " . $number;
        $this->sendRequest($message);
    }

    protected function sendEndRequest() : void
    {
        $message = "DONE " . $this->id;
        $this->sendRequest($message);
    }

    private function createSocket() : void
    {
        if(!$this->socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP ) )
        {
            throw new Exceptions\SocketException( $this->socketLastError() );
        }
    }

    private function setSocketOptions() : void
    {
        if( !socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, [
            'sec' => $this->options['timeout'],
            'usec' => 0
        ]) )
        {
            throw new Exceptions\SocketException( $this->socketLastError() );
        }

        if( !socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, [
            'sec' => $this->options['timeout'],
            'usec' => 0
        ]) )
        {
            throw new Exceptions\SocketException( $this->socketLastError() );
        }
    }

    private function socketLastError() : string
    {
        return socket_strerror( socket_last_error( $this->socket ) );
    }

    private function close() : void
    {
        socket_close($this->socket);
    }

    private function sendRequest( string $message ) : void
    {
        if( !socket_sendto($this->socket, $message, strlen($message), 0, $this->host, $this->port ) )
        {
            throw new Exceptions\SocketException( $this->socketLastError() );
        }
    }

    protected function waitForResponse( string $request, string $response ) : string
    {
        for($i = 1; $i <= 30; $i++)
        {
            if( !socket_recvfrom( $this->socket, $buffer, 2048, 0, $fromip, $fromport ) )
            {
                throw new SocketException( $this->socketLastError() );
            }

            if( substr( $buffer, 0, ( 6 + strlen( $this->id ) ) ) === "ERROR " . $this->id )
            {
                throw new GoipException( 'Error in ' . $request . ' request: ' . $buffer );
            }
            elseif( substr( $buffer, 0, ( 1 + strlen( $response ) + strlen( $this->id ) ) ) === $response . " " . $this->id )
            {
                return $buffer;
            }
        }

        throw new TimeoutException('Timeout on request: ' . $request);
    }
}
