<?php

namespace app\common\services;

use think\facade\Request;

class UserCode extends BaseServices
{
    public static function getCode()
    {
        return sprintf(
            config('wx.get_code_url'),
            config('wx.app_id'),
            Request::domain().config('wx.redirect_uri')
        );
    }
}
