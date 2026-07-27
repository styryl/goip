<?php

declare(strict_types=1);

namespace Pikart\Goip;

use LogicException;
use Pikart\Goip\Contracts\MessageDispatcher;
use Pikart\Goip\Contracts\MessageFactory;
use Pikart\Goip\Contracts\ServerFactory as ServerFactoryContract;
use ReflectionClass;
use ReflectionException;

class ServerFactory implements ServerFactoryContract
{
    private MessageFactory $messageFactory;

    private MessageDispatcher $messageDispatcher;

    public function __construct(MessageFactory $messageFactory, MessageDispatcher $messageDispatcher)
    {
        $this->messageDispatcher = $messageDispatcher;
        $this->messageFactory = $messageFactory;
    }

    /**
     * @param class-string $serverClass Implementation of Server
     * @param string $host Host to bind
     * @param int $port Port to bind
     * @param mixed[] $args Additional parameters
     * @throws ReflectionException
     */
    public function make(string $serverClass, string $host, int $port, array $args = []): Server
    {
        $server = $this->resolve($serverClass, $args);
        $server->setHost($host);
        $server->setPort($port);
        $server->setMessageFactory($this->messageFactory);
        $server->setMessageDispatcher($this->messageDispatcher);
        return $server;
    }

    /**
     * @param class-string $serverClass
     * @param mixed[] $args
     * @throws ReflectionException
     */
    private function resolve(string $serverClass, array $args = []): Server
    {
        $reflection = new ReflectionClass($serverClass);
        $server = $reflection->getConstructor() !== null
            ? $reflection->newInstanceArgs($args)
            : $reflection->newInstance();
        if (! $server instanceof Server) {
            throw new LogicException('Cannot create server implementation.');
        }

        return $server;
    }

    /**
     * @param class-string $serverClass
     * @param mixed[] $args
     * @throws ReflectionException
     */
    public static function default(string $serverClass, string $host, int $port, array $args = []): Server
    {
        $factory = new ServerFactory(new DefaultMessageFactory(), new DefaultMessageDispatcher());
        return $factory->make($serverClass, $host, $port, $args);
    }
}
