<?php

declare(strict_types=1);

namespace Pikart\Goip;

use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageFactory;

abstract class Server
{
    /**
     * Port to bind
     */
    protected int $port;

    /**
     * Host to bind
     */
    protected string $host;

    /**
     * Message dispatcher instance
     */
    protected MessageDispatcher $messageDispatcher;

    /**
     * Message factory instance
     */
    protected MessageFactory $messageFactory;

    /**
     * Flag to stop server
     */
    protected bool $stop = false;

    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * Set message dispatcher instance
     */
    public function setMessageDispatcher(MessageDispatcher $dispatcher): void
    {
        $this->messageDispatcher = $dispatcher;
    }

    /**
     * Set message factory instance
     */
    public function setMessageFactory(MessageFactory $factory): void
    {
        $this->messageFactory = $factory;
    }

    /**
     * Stop server
     */
    public function stop(): void
    {
        $this->stop = true;
    }

    /**
     * Register an message listener with the dispatcher for concrete message.
     */
    public function listen(string $type, object $listener): string
    {
        return $this->messageDispatcher->listen($type, $listener);
    }

    /**
     * Register an message listener with the dispatcher for all messages
     */
    public function listenAll(object $listener): string
    {
        return $this->messageDispatcher->listenAll($listener);
    }

    /**
     * Remove message listener by id
     */
    public function off(string $listenerIdentifier): void
    {
        $this->messageDispatcher->remove($listenerIdentifier);
    }

    /**
     * Get dispatcher instance
     */
    public function dispatcher(): MessageDispatcher
    {
        return $this->messageDispatcher;
    }

    /**
     * Dispatch messages to listeners
     */
    protected function dispatch(Request $request): void
    {
        $message = $this->messageFactory->make($request);

        if ($message->ack() !== null) {
            $this->send($message->ack(), $message->request()->host(), $message->request()->port());
        }

        $this->messageDispatcher->dispatch($message);
    }

    /**
     * Run server
     */
    abstract public function run(): void;

    /**
     * Send message to client
     */
    abstract protected function send(string $message, string $host, int $port): void;
}
