<?php


namespace app\api\controller;

use app\api\validate\User as UserValidate;
use app\common\lib\Show;
use app\common\model\Jsxxb1 as JsxxbModel;
use app\common\model\Xsxxb1 as XsxxbModel;
use app\common\services\User as UserServices;
use app\common\services\UserCode;
use app\common\services\UserToken;
use think\facade\Request;

class Token extends ApiBase
{
    /**
     * 获取code
     * @return \think\response\Json|\think\response\Redirect
     */
    public function code()
    {
        $code = input('code', '', 'trim');
        if (!$code) {
            $url = UserCode::getCode();
            return redirect($url);
        }
        
        return Show::success(['code' => $code]);
    }

    /**
     * 获取token
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get()
    {
        $code = input('code', '', 'trim');
        if (!$code) {
            return Show::error('code不可为空');
        }
        $ut = new UserToken();
        $token = $ut->getToken($code);
        return Show::success(['token' => $token]);
    }

    /**
     * 获取token
     * @return \think\response\Json
     */
    public function check()
    {
        $token = Request::header('Authorization');
        if (!$token) {
            return Show::error('token不可为空');
        }
        $ut = new UserToken();
        $res = $ut->checkToken($token);
        return Show::success(['res' => $res]);
    }

    /**
     * 校验账号是否绑定
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function isBind()
    {
        $token = Request::header('Authorization');
        if (!$token) {
            return Show::error('token不可为空');
        }

        $ut = new UserToken();
        $res = $ut->isBind($token);
        return Show::success(['res' => $res]);
    }

    /**
     * 账号绑定
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function bind()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $data = input('param.');
        $username = '';

        $validate = new UserValidate();
        if (!$validate->scene('bind')->check($data)) {
            return Show::error($validate->getError());
        }

        switch ($data['type']) {
            case 1:
                $info = (new JsxxbModel())->getByZGH($data['number'], $data['flag']);
                break;
            case 2:
                $info = (new XsxxbModel())->getByXH($data['number'], $data['flag']);
                break;
            default:
                $info = false;
                break;
        }

        if (!$info && !in_array($data['number'], config('status.white_list'))) {
            return Show::error('账号不存在');
        }

        if ($data['number'] == '00100') {
            $username = '沈蒙';
        }

        $data['username'] = $info->XM??$username;
        $data['identity_card'] = $info->SFZH??'';
        $data['mobile'] = $info->LXDH??'';

        $uid = $this->getUid();
        if (!$uid) {
            return Show::error('账号不存在');
        }

        if (isset($data['flag'])) {
            unset($data['flag']);
        }

        $user = (new UserServices())->update($uid, $data);

        if (!$user) {
            return Show::error('绑定失败');
        }
        return Show::success();
    }

    protected function getUid()
    {
        $userInfo = cache(config('wx.api_token_pre').$this->request->header('Authorization'));
        if (!$userInfo) {
            return 0;
        }
        $uid = $userInfo['uid'];
        return $uid;
    }
}
