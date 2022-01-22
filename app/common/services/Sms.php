<?php
declare(strict_types = 1);

namespace app\common\services;

use app\common\lib\ClassArr;
use app\common\lib\Num;
use app\common\lib\sms\AliSms;

class Sms
{
    public static function sendCode(string $phone, int $len, $type = 'ali') : bool
    {
        $code = Num::getCode($len);
        //		$sms = AliSms::sendCode($phone, $code);
        
        //工厂
        //		$type = ucfirst($type);
        //		$class = "app\common\lib\sms\\" . $type . "Sms";
        //		$sms = $class::sendCode($phone, $code);
        $classStats = ClassArr::smsClassStat();
        $classObj = ClassArr::initClass($type, $classStats);
        $sms = $classObj::sendCode($phone, $code);
        if ($sms) {
            //			cache(config('redis.code_pre').$phone, $code);
            cache(config('redis.code_pre').$phone, $code, config('redis.code_expire'));
        }
        return $sms;
    }
}
