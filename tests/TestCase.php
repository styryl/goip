<?php
namespace Pikart\Goip\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function invokeMethod( &$object, string $methodName, array $parameters = [] )
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function sampleBuffers( string $type ) : ? string
    {
        $buffers = [
            'req'     => 'req:27;id:gateway_name;pass:gateway_password;num:;signal:29;gsm_status:LOGIN;voip_status:LOGOUT;voip_state:IDLE;remain_time:-1;imei:867495023958030;imsi:260060172091324;iccid:89480611000720913242;pro:POL;idle:11;disable_status:0;SMS_LOGIN:N;SMB_LOGIN:;CELLINFO:LAC:7E5,CELL ID:5E5B;CGATT:Y;',
            'state'   => 'STATE:446;id:c1;password:gateway_password;gsm_remain_state:DIALING:999999999',
            'record'  => 'RECORD:445;id:c1;password:gateway_password;dir:2;num:999999999',
            'hangup'  => 'HANGUP:442;id:c1;password:gateway_password;num:,cause:"Normal call clearing',
            'receive' => 'RECEIVE:441;id:c1;password:gateway_password;srcnum:100;msg:This is sample sms message',
            'deliver' => 'DELIVER:429;id:c1;password:gateway_password;sms_no:3;state:0;num:999999999',
            'other'   => 'not supported message from goip?',
        ];

        if( key_exists( $type, $buffers ) )
        {
            return $buffers[ $type ];
        }

        return null;
    }


}
