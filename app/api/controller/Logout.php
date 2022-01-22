<?php


namespace app\api\controller;

class Logout extends AuthBase
{
    public function index()
    {
        $res = cache(config('rediskey.token_pre').$this->accessToken, null);
        if ($res) {
            return show(config("status.success"), "退出登录成功");
        }
        return show(config("status.error"), "退出登录失败");
    }
}
