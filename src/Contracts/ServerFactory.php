<?php

namespace Pikart\Goip\Contracts;
use Pikart\Goip\Server;

interface ServerFactory
{
    public function make( string $serverClass, string $host, int $port, array $args = [] ) : Server;
}
