<?php

namespace app\common\lib;

//
class Key
{
    /**
     * 记录参与抽奖用户取号码的redis key
     * @param $lotteryId
     * @return string
     */
    public static function LotteryNumIncrKey($lotteryId)
    {
        return config('rediskey.lottery_incr_by_id') . $lotteryId;
    }

    /**
     * 记录参与抽奖用户取号码的redis key
     * @param $lotteryId
     * @return string
     */
    public static function LotteryKey($lotteryId)
    {
        return config('rediskey.lottery_by_id') . $lotteryId;
    }

    /**
     * 后台查看权限密码 key
     * @return string
     */
    public static function SalaryPassWordKey()
    {
        return config('rediskey.salary_password');
    }
}
