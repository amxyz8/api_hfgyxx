<?php
namespace app\admin\controller;

use app\common\lib\Show;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use think\App;

class Wechat extends AdminAuthBase
{
    protected $config;
    public function __construct()
    {
        $this->config = config('wx');
    }

    public function menu()
    {
        $url = sprintf($this->config['get_at_url'], config('wx.app_id'), config('wx.app_secret'));
        $client = new Client();
        $response = $client->get($url);
        $at = json_decode($response->getBody(), true);
        $accessToken = $at['access_token'];

        $menuUrl = sprintf($this->config['get_custom_menu'], $accessToken);
        $menuRes = $client->get($menuUrl);
        $res = json_decode($menuRes->getBody(), true);

        return Show::success($res);
    }

    public function save()
    {
        $json = '
            {
                "button": [
                    {
                        "name": "微主页",
                        "type": "view",
                        "url": "http://web.hfgyxx.net/#/"
                    },
                    {
                        "name": "微服务",
                        "type": "view",
                        "url": "http://web.hfgyxx.net/#/pages/wserve/index/index"
                    },
                    {
                        "name": "微生活",
                        "type": "view",
                        "url": "http://web.hfgyxx.net/#/pages/wlife/index/index"
                    }
                ]
            }
        ';

        $url = sprintf($this->config['get_at_url'], config('wx.app_id'), config('wx.app_secret'));
        $client = new Client();
        $response = $client->get($url);
        $at = json_decode($response->getBody(), true);
        $accessToken = $at['access_token'];

        $menuUrl = sprintf($this->config['create_menu'], $accessToken);
//        $res = $client->post($menuUrl, [ RequestOptions::JSON => ['json' => $json]]);
        $res = curl_post_json($menuUrl, $json);
        return Show::success();
    }

    public function list()
    {
        $url = sprintf($this->config['get_at_url'], config('wx.app_id'), config('wx.app_secret'));
        $client = new Client();
        $response = $client->get($url);
        $at = json_decode($response->getBody(), true);
        $accessToken = $at['access_token'];

        $userListUrl = sprintf($this->config['user_list'], $accessToken, '');
        $userList = $client->get($userListUrl);
        $res = json_decode($userList->getBody(), true);

        return Show::success($res);
    }
}
