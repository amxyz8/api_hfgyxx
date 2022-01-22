<?php

namespace app\common\services;

use app\common\lib\Str;
use GuzzleHttp\Client;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;

class Wechat extends BaseServices
{
    protected $appId;
    protected $appSecret;
    protected $config;

    public function __construct()
    {
        $this->appId = config('wx.app_id');
        $this->appSecret = config('wx.app_secret');
        $this->config = config('wx');
    }

    /**
     * 模板消息
     * @param $openId
     * @param string $flag
     * @return int
     * @throws \Exception
     */
    public function Template($openId, $flag = 'approver')
    {
        $date = date('Y-m-d H:i');
        $url = 'http://web.hfgyxx.net/#/pages/wserve/maintain/index';
        $tempId = 'fw-S4KCn13bua3ny7cWSZdvqhHLi1AjYCt4Gyjvvgv8';
        if ($flag == 'approver') {
//            $tempId = '-DN1NXGKouDRI7FwODNlVBLn7kD3V1oyhQlGLqU7l7c';
            $value = '您有一个需要审批的请求';
            $data = '审批请求';
        } elseif ($flag == 'repair') {
//            $tempId = 'nrYCufbPi3qjhCqZ-6at1p4Enl9qsL-yAvKLYr3uL80';
            $value = '您有一个需要维修的任务';
            $data = '维修任务';
        } elseif ($flag == 'done') {
//            $tempId = 'mjhTXfBvPxlVTMwwYkl2nP2VHbo07aAODzgxT9KhVqA';
            $value = '您收到一个维修评价';
            $data = '维修评价';
        } else {
//            $tempId = '4ESW8ufYi9nxcXarlLJHljJ0Xojz4kw2XRcxzMSqZ9s';
            $value = '您有一个需要审批的请求';
            $data = '审批请求';
        }
        
        $arr = [
            'touser' => $openId,
            'template_id' => $tempId,
            'url' => $url,
            'data' => [
                'first' => [
                    "value" => $value,
                    "color" => "#173177"
                ],
                'event' => [
                    "value" => $data,
                    "color" => "#173177"
                ],
                'finish_time' => [
                    "value" => $date,
                    "color" => "#173177"
                ],
                'remark' => [
                    "value" => [],
//                    "color" => "#173177"
                ],
            ],
        ];
        
        $json = json_encode($arr);
        $url = sprintf($this->config['get_at_url'], $this->appId, $this->appSecret);
        $client = new Client();
        $response = $client->get($url);
        $at = json_decode($response->getBody(), true);
        $accessToken = $at['access_token'];
        
        $tempUrl = sprintf($this->config['template_url'], $accessToken);
        $res = curl_post_json($tempUrl, $json);
        $res = json_decode($res, true);
        if ($res['errcode']) {
            throw new \Exception('微信服务器接口调用失败');
            return 0;
        }
        return 1;
    }
}
