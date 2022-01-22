<?php


namespace app\api\controller;

use app\api\validate\user as UserValidate;
use app\BaseController;
use app\common\services\user as UserServices;

class Login extends BaseController
{
    public function index()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '非法请求');
        }
        $phone = $this->request->param('phone_number', '', 'trim');
        $code = input('param.code', 0, 'intval');
        $type = input('param.type', 0, 'intval');
         
        $data = [
            'phone_number' => $phone,
            'code' => $code,
            'type' => $type,
        ];
        
        $validate = new UserValidate();
        if (!$validate->scene('login')->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        
        try {
            $result = (new UserServices())->login($data);
        } catch (\Exception $e) {
            return show($e->getCode(), $e->getMessage());
        }
        if ($result) {
            return show(config('status.success'), '登录成功', $result);
        }
        return show(config('status.error'), '登录失败');
    }
}
