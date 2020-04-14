<?php

namespace Pikart\Goip\Contracts;
use Pikart\Goip\Server;

interface ServerMaker
{
    public function make( ServerBuilder $builder ) : Server;
}
