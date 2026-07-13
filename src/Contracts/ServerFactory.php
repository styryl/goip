<?php

declare(strict_types=1);

namespace Pikart\Goip\Contracts;

use Pikart\Goip\Server;

interface ServerFactory
{
    /**
     * @param class-string $serverClass
     * @param mixed[] $args
     */
    public function make(string $serverClass, string $host, int $port, array $args = []): Server;
}
