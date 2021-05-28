<?php

declare(strict_types=1);

namespace Pikart\Goip\Contracts;

use Pikart\Goip\Message;

/**
 * @package Pikart\Goip\Contracts
 */
interface MessageDispatcher
{
    /**
     * @param object $listener
     * @return string
     */
    public function listenAll(object $listener): string;

    /**
     * @param string $type
     * @param object $listener
     * @return string
     */
    public function listen(string $type, object $listener): string;

    /**
     * @return mixed[]
     */
    public function listeners(): array;

    /**
     * @param Message $message
     */
    public function dispatch(Message $message): void;

    /**
     * @param string|null $eventType
     */
    public function removeAll(?string $eventType = null): void;

    /**
     * @param string $listenerIdentifier
     */
    public function remove(string $listenerIdentifier): void;

    /**
     * @param string $listenerIdentifier
     * @return object|null
     */
    public function get(string $listenerIdentifier): ?object;
}
