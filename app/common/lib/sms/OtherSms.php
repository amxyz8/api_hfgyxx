<?php
declare(strict_types = 1);

namespace app\common\lib\sms;

class OtherSms implements SmsBase
{
    public static function sendCode(string $phone, int $code)
    {
        // TODO: Implement sendCode() method.
        return true;
    }
}
