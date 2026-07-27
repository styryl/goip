<?php

declare(strict_types=1);

namespace Pikart\Goip;

use Pikart\Goip\Contracts\MessageFactory;
use Pikart\Goip\Messages\DeliverMessage;
use Pikart\Goip\Messages\ExpiryMessage;
use Pikart\Goip\Messages\HangupMessage;
use Pikart\Goip\Messages\NotSupportedMessage;
use Pikart\Goip\Messages\ReceiveMessage;
use Pikart\Goip\Messages\RecordMessage;
use Pikart\Goip\Messages\RequestMessage;
use Pikart\Goip\Messages\StateMessage;

class DefaultMessageFactory implements MessageFactory
{
    /**
     * @var array<string, class-string<Message>>
     */
    private const array MESSAGES = [
        'req' => RequestMessage::class,
        'state' => StateMessage::class,
        'record' => RecordMessage::class,
        'hangup' => HangupMessage::class,
        'receive' => ReceiveMessage::class,
        'deliver' => DeliverMessage::class,
        'expiry' => ExpiryMessage::class,
    ];

    public function make(Request $request): Message
    {
        $type = (string) array_key_first($request->all());

        if (array_key_exists($type, self::MESSAGES)) {
            return new (self::MESSAGES[$type])($request);
        }

        return new NotSupportedMessage($request);
    }
}
