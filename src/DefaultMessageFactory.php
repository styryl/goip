<?php

declare(strict_types=1);

namespace Pikart\Goip;

use Pikart\Goip\Messages\NotSupportedMessage;
use Pikart\Goip\Contracts\MessageFactory;
use Pikart\Goip\Messages\DeliverMessage;
use Pikart\Goip\Messages\ReceiveMessage;
use Pikart\Goip\Messages\RequestMessage;
use Pikart\Goip\Messages\HangupMessage;
use Pikart\Goip\Messages\RecordMessage;
use Pikart\Goip\Messages\StateMessage;

/**
 * @package Pikart\Goip
 */
class DefaultMessageFactory implements MessageFactory
{
    /**
     * The available message types
     *
     * @var array<string, class-string>
     */
    protected array $messages = [
        'req' => RequestMessage::class,
        'state' => StateMessage::class,
        'record' => RecordMessage::class,
        'hangup' => HangupMessage::class,
        'receive' => ReceiveMessage::class,
        'deliver' => DeliverMessage::class,
    ];

    /**
     * Make concrete message from Request
     *
     * @param Request $request
     * @return Message
     */
    public function make(Request $request): Message
    {
        // First key of parsed buffer is type of message
        $type = (string)array_key_first($request->all());

        if (array_key_exists($type, $this->messages)) {
            return new $this->messages[$type]($request);
        }

        return new NotSupportedMessage($request);
    }
}
