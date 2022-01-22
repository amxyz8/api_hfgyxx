<?php

namespace app\common\services;

use app\common\lib\Str;
use GuzzleHttp\Client;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;

class UserToken extends BaseServices
{
    protected $appId;
    protected $appSecret;

    public function __construct()
    {
        $this->appId = config('wx.app_id');
        $this->appSecret = config('wx.app_secret');
    }

    /**
     * @param $code
     * @return string
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getToken($code)
    {
        $url = sprintf(config('wx.get_token_url'), $this->appId, $this->appSecret, $code);
        $client = new Client();
        $response = $client->get($url);
        $wxResult = json_decode($response->getBody(), true);
        if (empty($wxResult)) {
            throw new \Exception('获取openid异常, 微信内部错误');
        }
        
        $loginFail = array_key_exists('errcode', $wxResult);
        if ($loginFail) {
            throw new \Exception('微信服务器接口调用失败');
        }
        
        return $this->grantToken($wxResult);
    }
    
    /**
     * 颁发令牌
     * @param $wxResult
     * @return string
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function grantToken($wxResult)
    {
        //拿到openid
        //数据库查看openid是否存在
        //如存在, 不作处理, 不存在, 新增user记录
        //生成令牌, 准备缓存数据, 写入缓存
        //把令牌返回客户端
        $openid = $wxResult['openid'];
        $userService = new User();
        $user = $userService->getNormalUserByOpenId($openid);
        if ($user) {
            $uid = $user['id'];
        } else {
            $data = [
                'openid' => $openid,
//        		'nickname' => $wxResult['nickname'],
//		        'headimgurl' => $wxResult['headimgurl'],
            ];
            $uid = $userService->add($data);
        }
        
        $cachedValue = self::cachedWxValue($wxResult, $uid);
        $token = self::saveToCache($cachedValue);
        return $token;
    }
    
    //缓存微信数据
    public function cachedWxValue($wxResult, $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        return $cachedValue;
    }
    
    public static function saveToCache($cachedValue)
    {
        $str = Str::generateToken();
        $expire = config('wx.token_expire_in');
        $request = cache(config('wx.api_token_pre') . $str, $cachedValue, $expire);
        if (!$request) {
            throw new Exception("数据不存在");
        }
        return $str;
    }
    
    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function getCurrentTokenVar($key)
    {
        $token = Request::header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new Exception('token已过期或无效');
        }
        if (!is_array($vars)) {
            $vars = json_decode($vars, true);
        }
        if (!array_key_exists($key, $vars)) {
            throw new Exception('尝试获取的token变量并不存在');
        }
        return $vars[$key];
    }
    
    //获取当前登录用户的uid
    public function getCurrentUid()
    {
        $uid = $this->getCurrentTokenVar('uid');
        return $uid;
    }

    //检验token是否有效
    public function checkToken($token)
    {
        $key = config('wx.api_token_pre') . $token;

        return Cache::get($key) ? 1 : 0;
    }

    /**
     * 校验是否绑定
     * @param $token
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function isBind($token)
    {
        $redisInfo = Cache::get(config('wx.api_token_pre').$token);
        if (!$redisInfo) {
            return 0;
        }
        $uid = $redisInfo['uid'];
        $user = (new \app\common\services\User())->getNormalUserById($uid);
        if ($user['number']) {
            return 1;
        }
        return 0;
    }
}
