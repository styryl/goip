This package allow to send and receive SMS messages using GSM / VOIP Goip1, Goip4, Goip8, Goip16 gateways from Hybertone / Dbltek company. SMS can be received via the UDP protocol using php sockets or reactphp. The SMS can be sent via UDP or HTTP. The package is used in a production environment with four Goip16 gateways connected (64 lines). Additionally, it is possible to receive basic information about the state and status of individual GSM gates (lines).

## Requirements

```bash
PHP >= 7.4
```

## Installation

```bash
composer require pikart/goip
```

## Server usage instruction

To start receiving messages from Goip gateways you need to create and start a server. 

Two implementations are possible:

```php
 \Pikart\Goip\UdpServer::class // php sockets
 \Pikart\Goip\ReactServer::class // reactphp
```

Creating a server using php sockets:

```php
$server = \Pikart\Goip\ServerFactory::default( \Pikart\Goip\UdpServer::class, '0.0.0.0', 333);
```

Creating a server using reactphp:

```php
$server = \Pikart\Goip\ServerFactory::default( \Pikart\Goip\ReactServer::class, '0.0.0.0', 333);
```

Method  **\Pikart\Goip\ServerFactory::default() : Server** creates new Server instance, it takes four parameters:

1. $serverClass (**string**) - Server implementation \Pikart\Goip\Server::class
2. $host (**string**) - server host address
3. $port (**int**) - server port
4. $args (**array**) optional - additional parameters

The server allows to listen messages received from Goip trough the Observer pattern.

#### Register message listener

```php
use Pikart\Goip\ServerFactory;
use Pikart\Goip\ReactServer;
use Pikart\Goip\Message;
use Pikart\Goip\Messages\RequestMessage;
use Pikart\Goip\Messages\ReceiveMessage;

$server = ServerFactory::default( ReactServer::class, '0.0.0.0', 333);

// Listening to all incoming messages
$listenerId1 = $server->listenAll(function (Message $message){
    // Request message
    if( $message instanceof RequestMessage)
    {
        var_dump( $message );
    }

    // Receive message
    if( $message instanceof ReceiveMessage)
    {
        var_dump( $message );
    }

    // Message
    var_dump( $message );

});

// Listening to a concrete message
$listenerId2 = $server->listen( RequestMessage::class, function (RequestMessage $message){
    var_dump( $message );
});

$listenerId3 = $server->listen( ReceiveMessage::class, function (ReceiveMessage $message){
    var_dump( $message );
});

// Remove listener 1
$server->off($listenerId1);
// Remove listener 2
$server->off($listenerId2);
// Remove listener 3
$server->off($listenerId3);

```
It's highly recommended to use external queue system like RabbitMQ or Laravel Queues to process incoming messages.

#### Server startup

```php
use Pikart\Goip\ServerFactory;
use Pikart\Goip\ReactServer;
use Pikart\Goip\Message;

$server = ServerFactory::default( ReactServer::class, '0.0.0.0', 333);

// Listening to all incoming messages
$server->listenAll(function (Message $message){
    // Message
    var_dump( $message );
});

$server->run();
```

#### Message types

```php
\Pikart\Goip\Messages\RequestMessage::class // Keep Alive packets with gateway (line) information
\Pikart\Goip\Messages\DeliverMessage::class // SMS delivery report
\Pikart\Goip\Messages\HangupMessage::class // End telephone call
\Pikart\Goip\Messages\ReceiveMessage::class // Incoming SMS
\Pikart\Goip\Messages\RecordMessage::class // Start a phone call
\Pikart\Goip\Messages\StateMessage::class // Change of gate (line) status
\Pikart\Goip\Messages\NotSupportedMessage::class // Message not supported by package
\Pikart\Goip\Message::class // Main abstract class
```

#### Message attributes and properties

```php
use Pikart\Goip\ServerFactory;
use Pikart\Goip\ReactServer;
use Pikart\Goip\Message;
use Pikart\Goip\Messages\ReceiveMessage;

$server = ServerFactory::default( ReactServer::class, '0.0.0.0', 333);

$server->listenAll(function (Message $message){
    // Array of all attributes
    $attributes = $message->attributes();

    // Pikart\Goip\Request::class Access to raw request from goip
    $request = $message->request();

    // Show remote host and port
    $remoteHost = $message->request()->host();
    $remotePort = $message->request()->port();

});

// ReceiveMessage
$server->listen( ReceiveMessage::class, function ( ReceiveMessage $message ) {
    // Received text message
    $smsTextMessage = $message->msg();

    // Sender phone number
    $phoneNumber = $message->srcnum();

    // Password from goip
    $goipPassword = $message->password();

    // Show remote host and port
    $remoteHost = $message->request()->host();
    $remotePort = $message->request()->port();
});
```
## Sending a message

#### Sending via UDP socket

```php
use Pikart\Goip\Sms\SocketSms;

/**
* SocketSms constructor.
*
* @param string $host Goip host
* @param int $port Goip port
* @param string $id Unique sending session id
* @param string $password Goip password
* @param array|null $options
*/
$sms = new SocketSms(
    '192.168.0.11',
    9991,
    123,
    'passwod',
    ['timeout' => 30]
);

/**
* Send sms
*
* @param string $number Phone number
* @param string $message Text message
* @return array Response from goip
* @throws GoipException
* @throws SocketException
* @throws TimeoutException
*/
$response = $sms->send('999999999', 'text message');

var_dump( $response );

// response from goip
array(4) {
  ["sendid"]=>string(3) "123" // unique sendid
  ["telid"]=>string(1) "1" // session id
  ["sms_no"]=> string(1) "0" // sms count number
  ["raw"]=>string(12) "OK 123 1 0" // raw response
}

```

#### Sending via HTTP

```php
use Pikart\Goip\Sms\HttpSms;

/**
* HttpSms constructor.
*
* @param string $host Goip host for example: http://192.168.0.11
* @param int $line Goip line number,
* @param string $login Goip login
* @param string $password Goip password
*/
$sms = new HttpSms('http://192.168.0.11',1, 'admin', 'admin');

/**
* Send sms
*
* @param string $number Phone number
* @param string $message Text message
* @return array
* @throws GoipException
*/
$response = $sms->send('695772577', 'text message');

var_dump( $response );

array(3) {
    ["id"]=> string(8) "0000021f" // send id
    ["raw"]=> string(45) "Sending,L1 Send SMS to:695772577; ID:0000021f" // Raw response
    ["status"]=> string(4) "send" // send status
}
```
