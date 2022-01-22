<?php
declare(strict_types = 1);

namespace app\common\lib;

class Num
{
    public static function getCode(int $len = 4) : int
    {
        $code = rand(1000, 9999);
        if ($len == 6) {
            $code = rand(10000, 99999);
        }
        return $code;
    }

    public static function fixFourNum($num)
    {
        return str_pad((string)$num, 4, "0", STR_PAD_LEFT);
    }
}
