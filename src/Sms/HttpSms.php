<?php declare(strict_types=1);

namespace Pikart\Goip\Sms;
use Pikart\Goip\Contracts\Sms;

class HttpSms implements Sms
{
    private string $host;
    private string $line;
    private string $login;
    private string $password;
    
    public function __construct(string $host, int $line, string $login, string $password)
    {

    }

    public function send(string $number, string $message): array
    {
        return [];
    }
}
