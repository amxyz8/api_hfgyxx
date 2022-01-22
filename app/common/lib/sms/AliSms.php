<?php
declare(strict_types = 1);

namespace app\common\lib\sms;

class AliSms implements SmsBase
{
    public static function sendCode(string $phone, int $code)
    {
        return true;
    }
}
