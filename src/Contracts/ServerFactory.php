<?php

declare(strict_types=1);

namespace Pikart\Goip\Contracts;

use Pikart\Goip\Server;

/**
 * @package Pikart\Goip\Contracts
 */
interface ServerFactory
{
    /**
     * @param class-string $serverClass
     * @param string $host
     * @param int $port
     * @param mixed[] $args
     * @return Server
     */
    public function make(string $serverClass, string $host, int $port, array $args = []): Server;
}
