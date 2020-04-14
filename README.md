# Instalation

Requirements:

php >= 7.4

Require by composer
```bash
composer require pikart/goip
```

# Server usage instructions

**Server init**

First, create **\Pikart\Goip\Server::class** instance using **\Pikart\Goip\ServerFactory**

```PHP
$server = \Pikart\Goip\ServerFactory::default( \Pikart\Goip\ReactServer::class, '0.0.0.0', 333);
```

Static method **ServerFactory::default** takes three arguments:

1. Implementation \Pikart\Goip\ServerFactory (**string**)
    Raw PHP **\Pikart\Goip\UdpServer::class**
    ```PHP
    $server = \Pikart\Goip\ServerFactory::default( \Pikart\Goip\UdpServer::class, '0.0.0.0', 333);
    ```
    ReactPHP **\Pikart\Goip\ReactServer::class**
    ```PHP
    $server = \Pikart\Goip\ServerFactory::default( \Pikart\Goip\ReactServer::class, '0.0.0.0', 333);
    ```
2.  host (**string**)
    Host address to bind
3.  host (**int**)
    Port number to bind
4. args (**arrray**)
    Additional parameters required by implementations

**Listen for GoIP messages**

The server allows to listen for incoming messages from GoIP:

```php
$server->listen(\Pikart\Goip\Message::class, function ( \Pikart\Goip\Message $message ) {
    var_dump( $message );
});
```

Method **\Pikart\Goip\Server::listen** takes two parameters: 

1. Implementation \Pikart\Goip\Message::class (**string**)
2. Listener (**object**)
Listener must be object that is callable or implements MessageListener contract

Available message types:
```php
 \Pikart\Goip\RequestMessage::class, // Sent as KeepAlive with line informations
 \Pikart\Goip\StateMessage::class, // Sent when the line status changes
 \Pikart\Goip\RecordMessage::class, // Sent when a telephone connection has been made
 \Pikart\Goip\HangupMessage::class, // Sent when the telephone connection is completed
 \Pikart\Goip\ReceiveMessage::class, // Sent when the SMS was received from the sender
 \Pikart\Goip\DeliverMessage::class, // Sent when the sms has been delivered to the recipient
 \Pikart\Goip\NotSupportedMessage::class, // Sent when GoIP sends an unsupported message
 \Pikart\Goip\Message::class, // Listen all messages
```

Listen for all messages

```php
$server->listenAll(function ( \Pikart\Goip\Message $message ){
    var_dump($message);
});
```

**Server startup**

After creating the server, just call the method **\Pikart\Goip\Server::run**

```php
$server = \Pikart\Goip\ServerFactory::default( \Pikart\Goip\ReactServer::class, '0.0.0.0', 333);

// Delivery
$server->listen(\Pikart\Goip\Messages\ReceiveMessage::class, function ( \Pikart\Goip\Messages\DeliverMessage $message ) {
    var_dump( $message );
});

//All
$server->listenAll(function ( \Pikart\Goip\Message $message ){
    var_dump($message);
});

$server->run();
```

# Sending SMS usage instructions

**Send sms by socket UDP protocol**

```php
$sms = new \Pikart\Goip\Sms\SocketSms(
    '192.168.0.11', // GoIP host
    9991, // GoIP port
   123, // Unique id
    'pass', // GoIP password
);

$sms->send('999999999', 'text message');
```

**How to find the GoIP gateway UDP port number**

Start the server, connect the GoIP gateway and listen to the messages:

```php
$server = \Pikart\Goip\ServerFactory::default( \Pikart\Goip\ReactServer::class, '0.0.0.0', 333);

//All
$server->listenAll(function ( \Pikart\Goip\Message $message ){
    var_dump($message->request()->host()); // GOIP HOST
    var_dump($message->request()->port()); // GOIP PORT
});

$server->run();
```
**Send sms by HTTP protocol**

```php
$sms = new \Pikart\Goip\Sms\HttpSms('http://192.168.0.11',1,'admin','admin');
$response = $sms->send(999999999, 'test message');
```

The response will be contains array:

```php
array(3) {
  ["id"]=>string(8) "00001893"
  ["raw"]=>string(45) "Sending,L1 Send SMS to:695772577; ID:00001893"
  ["status"]=>string(4) "send"
}
```

If sms will be not send, the** \Pikart\Goip\Exceptions\GoipException::class** will be thrown.




