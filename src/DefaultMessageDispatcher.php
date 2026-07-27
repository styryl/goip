<?php

declare(strict_types=1);

namespace Pikart\Goip;

use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageListener;

class DefaultMessageDispatcher implements MessageDispatcher
{
    /**
     * @var array<string, array<string, object>>
     */
    private array $listeners = [
        Message::class => [],
    ];

    public function listen(string $type, object $listener): string
    {
        if (! is_callable($listener) && ! $listener instanceof MessageListener) {
            $exceptionMessage = 'Listener must be object that is callable or implements MessageListener contract';
            throw new \InvalidArgumentException($exceptionMessage);
        }

        $listenerIdentifier = $this->getHashId($listener);
        $this->listeners[$type][$listenerIdentifier] = $listener;
        return $listenerIdentifier;
    }

    public function listenAll(object $listener): string
    {
        return $this->listen(Message::class, $listener);
    }

    /**
     * @return array<string, array<string, object>>
     */
    public function listeners(): array
    {
        return $this->listeners;
    }

    public function removeAll(?string $type = null): void
    {
        if ($type !== null && array_key_exists($type, $this->listeners)) {
            unset($this->listeners[$type]);
            return;
        }

        $this->listeners = [];
    }

    public function remove(string $listenerIdentifier): void
    {
        foreach ($this->listeners as $type => $listeners) {
            if (array_key_exists($listenerIdentifier, $listeners)) {
                unset($this->listeners[$type][$listenerIdentifier]);
            }
        }
    }

    public function get(string $listenerIdentifier): ?object
    {
        foreach ($this->listeners as $listenerType) {
            if (array_key_exists($listenerIdentifier, $listenerType)) {
                return $listenerType[$listenerIdentifier];
            }
        }

        return null;
    }

    public function dispatch(Message $message): void
    {
        $listeners = $this->listeners[Message::class];

        $type = get_class($message);

        if ($type !== Message::class && key_exists($type, $this->listeners)) {
            $listeners = array_merge($listeners, $this->listeners[$type]);
        }

        foreach ($listeners as $listener) {
            if ($listener instanceof MessageListener) {
                $listener->onMessage($message);
                continue;
            }

            if (is_callable($listener)) {
                $listener($message);
            }
        }
    }

    private function getHashId(object $callable): string
    {
        return spl_object_hash($callable);
    }
}
