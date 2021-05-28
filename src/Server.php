<?php

declare(strict_types=1);

namespace Pikart\Goip;

use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageFactory;

/**
 * @package Pikart\Goip
 */
abstract class Server
{
    /**
     * Port to bind
     *
     * @var int
     */
    protected int $port;

    /**
     * Host to bind
     *
     * @var string
     */
    protected string $host;

    /**
     * Message dispatcher instance
     *
     * @var MessageDispatcher
     */
    protected MessageDispatcher $messageDispatcher;

    /**
     * Message factory instance
     *
     * @var MessageFactory
     */
    protected MessageFactory $messageFactory;

    /**
     * Flag to stop server
     *
     * @var bool
     */
    protected bool $stop = false;

    /**
     * Set port
     *
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * Set Host
     *
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * Set message dispatcher instance
     *
     * @param MessageDispatcher $dispatcher
     */
    public function setMessageDispatcher(MessageDispatcher $dispatcher): void
    {
        $this->messageDispatcher = $dispatcher;
    }

    /**
     * Set message factory instance
     *
     * @param MessageFactory $factory
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
     *
     * @param string $type
     * @param object $listener
     * @return string
     */
    public function listen(string $type, object $listener): string
    {
        return $this->messageDispatcher->listen($type, $listener);
    }

    /**
     * Register an message listener with the dispatcher for all messages
     *
     * @param object $listener
     * @return string
     */
    public function listenAll(object $listener): string
    {
        return $this->messageDispatcher->listenAll($listener);
    }

    /**
     * Remove message listener by id
     *
     * @param string $listenerIdentifier
     */
    public function off(string $listenerIdentifier): void
    {
        $this->messageDispatcher->remove($listenerIdentifier);
    }

    /**
     * Get dispatcher instance
     *
     * @return MessageDispatcher
     */
    public function dispatcher(): MessageDispatcher
    {
        return $this->messageDispatcher;
    }

    /**
     * Dispatch messages to listeners
     *
     * @param Request $request
     */
    protected function dispatch(Request $request): void
    {
        $message = $this->messageFactory->make($request);

        if (!is_null($message->ack())) {
            $this->send($message->ack() ?? "", $message->request()->host(), $message->request()->port());
        }

        $this->messageDispatcher->dispatch($message);
    }

    /**
     * Run server
     */
    abstract public function run(): void;

    /**
     * Send message to client
     *
     * @param string $message
     * @param string $host
     * @param int $port
     */
    abstract protected function send(string $message, string $host, int $port): void;
}
