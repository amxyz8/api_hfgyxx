<?php
namespace app\api\controller;

use app\BaseController;
use app\common\lib\Show;
use app\common\services\UserToken;
use EasyWeChat\Factory;

class Wechat extends ApiBase
{
    public function code()
    {
        $config = [
            'app_id' => config('wechat.app_id'),
            'secret' => config('wechat.secret'),
            'oauth' => [
                'scopes'   => config('wechat.oauth.scopes'),
                'callback' => config('wechat.oauth.callback'),
            ],
        ];

        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;
        return $oauth->redirect();
    }

    public function token()
    {
        $config = [
            'app_id' => config('wechat.app_id'),
            'secret' => config('wechat.secret'),
        ];

        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;

        $token = UserToken::grantToken($oauth->user()->getOriginal());

        // 获取 OAuth 授权结果用户信息
//        return show(config("status.success"), "ok", $oauth->user());
        return Show::success(['token' => $token]);
//        header('location:'. 'api/wechat/code');
    }
}
