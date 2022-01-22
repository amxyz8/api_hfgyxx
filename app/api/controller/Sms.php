<?php


namespace app\api\controller;

use app\api\validate\AdminUser;
use app\common\services\Sms as SmsServices;
use think\exception\ValidateException;

class Sms
{
    public function code()
    {
        $phone = input('param.phone_number', '', 'trim');
        $data = [
            'phone_number' => $phone
        ];
        
        try {
            validate(AdminUser::class)->scene('send_code')->check($data);
        } catch (ValidateException $e) {
            return show(config('status.error'), $e->getError());
        }
        
        if (SmsServices::sendCode($phone, 6, 'ali')) {
            return show(config('status.success'), '发送成功');
        }
        return show(config('status.error'), '发送失败');
    }
}
