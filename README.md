# GoIP

PHP client and UDP server for Hybertone / Dbltek GoIP GSM gateways.

The package can receive gateway messages over UDP and send SMS messages through
the GoIP HTTP or UDP SMS APIs. It supports GoIP1, GoIP4, GoIP8 and GoIP16
devices.

## Features

- Receive GoIP UDP packets with PHP sockets or ReactPHP
- Send SMS messages through the GoIP HTTP API
- Send SMS messages through the GoIP UDP socket API
- Handle incoming SMS, delivery reports, call events and line state changes
- Run a local debug receiver from the command line

## Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Receiving Messages](#receiving-messages)
- [Sending SMS](#sending-sms)
- [Debug CLI](#debug-cli)
- [Development](#development)

## Requirements

- PHP `^8.5`
- `ext-sockets`

## Installation

```bash
composer require pikart/goip
```

## Quick Start

Start a UDP receiver:

```php
use Pikart\Goip\Message;
use Pikart\Goip\ReactServer;
use Pikart\Goip\ServerFactory;

$server = ServerFactory::default(ReactServer::class, '0.0.0.0', 44444);

$server->listenAll(static function (Message $message): void {
    var_dump($message->attributes());
});

$server->run();
```

Send an SMS over HTTP:

```php
use Pikart\Goip\Sms\HttpSms;

$sms = new HttpSms('http://192.168.10.23', 1, 'goip-admin', 'secret');

$response = $sms->send('+15551234567', 'Your verification code is 482913.');
```

## Receiving Messages

Two server implementations are available:

| Server | Runtime | Use when |
| --- | --- | --- |
| `Pikart\Goip\UdpServer` | PHP sockets | You want the smallest blocking server |
| `Pikart\Goip\ReactServer` | ReactPHP | You already use a ReactPHP event loop |

```php
use Pikart\Goip\Messages\ReceiveMessage;
use Pikart\Goip\ReactServer;
use Pikart\Goip\ServerFactory;

$server = ServerFactory::default(ReactServer::class, '0.0.0.0', 44444);

$server->listen(ReceiveMessage::class, static function (ReceiveMessage $message): void {
    $sender = $message->srcnum();
    $text = $message->msg();
});

$server->run();
```

To listen for every supported message:

```php
use Pikart\Goip\Message;

$server->listenAll(static function (Message $message): void {
    $type = $message::class;
    $attributes = $message->attributes();
    $remoteHost = $message->request()->host();
    $remotePort = $message->request()->port();
});
```

Listeners can be removed by their returned identifier:

```php
$listenerId = $server->listenAll($listener);

$server->off($listenerId);
```

### Factory

```php
ServerFactory::default(string $serverClass, string $host, int $port, array $args = []): Server
```

Supported message classes:

- `Pikart\Goip\Messages\RequestMessage` - keep-alive packets with gateway line information
- `Pikart\Goip\Messages\ReceiveMessage` - incoming SMS
- `Pikart\Goip\Messages\DeliverMessage` - SMS delivery report
- `Pikart\Goip\Messages\HangupMessage` - call end
- `Pikart\Goip\Messages\RecordMessage` - call start
- `Pikart\Goip\Messages\StateMessage` - gateway line state change
- `Pikart\Goip\Messages\ExpiryMessage` - expiry notification
- `Pikart\Goip\Messages\NotSupportedMessage` - unsupported packet type

For production receivers, keep listener work short and hand off heavier
processing to a queue.

## Sending SMS

Both SMS clients implement `Pikart\Goip\Contracts\Sms`:

```php
public function send(string $number, string $message): array;
```

### HTTP

```php
use Pikart\Goip\Sms\HttpSms;

$sms = new HttpSms('http://192.168.10.23', 1, 'goip-admin', 'secret');
$sms->setGuzzleTimeout(5);

$response = $sms->send('+15551234567', 'Your verification code is 482913.');
```

Successful response:

```php
[
    'id' => '0000021f',
    'raw' => 'Sending,L1 Send SMS to:+15551234567; ID:0000021f',
    'status' => 'send',
]
```

### UDP Socket

```php
use Pikart\Goip\Sms\SocketSms;

$sms = new SocketSms(
    '192.168.10.23',
    9991,
    '123',
    'secret',
    ['timeout' => 5]
);

$response = $sms->send('+15551234567', 'Your verification code is 482913.');
```

Successful response:

```php
[
    'sendid' => '123',
    'telid' => '1',
    'sms_no' => '0',
    'raw' => 'OK 123 1 0',
]
```

## Debug CLI

The repository includes a small development CLI:

```bash
php bin/goip-debug debug:server --host=0.0.0.0 --port=44444 --driver=udp
php bin/goip-debug debug:server --host=0.0.0.0 --port=44444 --driver=react
```

It prints received packets as JSON. CLI options override environment variables.

Server options:

| Option | Env | Default |
| --- | --- | --- |
| `--host` | `GOIP_DEBUG_HOST` | `0.0.0.0` |
| `--port` | `GOIP_DEBUG_PORT` | `44444` |
| `--driver` | `GOIP_DEBUG_DRIVER` | `udp` |
| `--sleep` | `GOIP_DEBUG_SLEEP` | `5000` |

Docker defaults for the debug server can be configured in `.env`:

```bash
cp .env.example .env
make debug_server
```

Manual SMS checks can be run with:

```bash
php bin/goip-debug debug:send-sms --transport=http --host=http://192.168.10.23 --line=1 --login=goip-admin --password=secret --to=+15551234567 --message="Your verification code is 482913."
php bin/goip-debug debug:send-sms --transport=socket --host=192.168.10.23 --port=9991 --session=sms-20260727-001 --password=secret --to=+15551234567 --message="Your verification code is 482913."
```

## Development

```bash
make build
make install
make test
```

Run checks separately:

```bash
make ecs
make phpmd
make phpstan
make phpunit
```
