<?php


namespace Pikart\Goip\Tests;


class GoipServer
{
    private $socket;
    private int $step = 1;

    public function __construct( string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;

        $this->socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
        socket_bind( $this->socket, $this->host, $this->port );

        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, [
            'sec'  => 1,
            'usec' => 0
        ]);
    }

    public function sendTo( string $message, ?string $host = null, ?int $port = null ) : int
    {
        return socket_sendto($this->socket, $message, strlen($message), 0, $host ?? $this->host, $port ?? $this->port );
    }

    public function receiveFrom( bool $sendError = false ) : string
    {
        socket_recvfrom( $this->socket, $buffer, 2048, 0, $fromip, $fromport );


        // BulkSMSRequest Format: MSG $sendid $length $msg\n
        if( strpos($buffer, "MSG") !== false )
        {
            $arr = explode( ' ', $buffer);

            // BulkSMSRequest ERROR $sendid $errormsg\n
            if( $sendError )
            {
                $this->sendTo('ERROR '.$arr[1] .' Error message' . PHP_EOL , $fromip, $fromport);
            }
            else
            {
                $this->sendTo('PASSWORD '.$arr[1] . PHP_EOL , $fromip, $fromport);
            }
        }

        // AuthenticationRequest Format: PASSWORD $sendid $password\n
        if( strpos($buffer, "PASSWORD") !== false )
        {
            $arr = explode( ' ', $buffer);
            $this->sendTo('SEND '.$arr[1] . PHP_EOL, $fromip, $fromport);
        }

        // SubmitNumberRequest Format: SEND $sendid $telid $telnum\n
        if( strpos($buffer, "SEND") !== false )
        {
            $arr = explode( ' ', $buffer);
            $this->sendTo('OK '.$arr[1].' '.$arr[2] .' 123' . PHP_EOL, $fromip, $fromport);
        }

        // EndReqeust Format: DONE $sendid\n
        if( strpos($buffer, "DONE") !== false )
        {
            $arr = explode( ' ', $buffer);
            $this->sendTo('DONE '.$arr[1], $fromip, $fromport);
        }

        return $buffer;
    }

    public function BulkSMSRequest()
    {

    }

    public function close() : void
    {
        socket_close( $this->socket );
    }

}
