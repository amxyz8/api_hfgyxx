<?php

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username' => 'require',
        'phone_number' => 'require',
        'number' => 'require',
        'flag' => 'require',
        'code'  =>  'require|number|min:4',
        'type' => ["require", "in"=>"1,2"], // 两种不同的方式而已
        'sex' => ["require", "in"=>"0,1,2"],
    ];
    
    protected $message = [
        'username' => '用户名必须',
        'phone_number' => '电话号码必须',
        'number' => 'number不可为空',
        'flag' => '身份标识不可为空',
        'code.require' => '短信验证码必须',
        'code.number'  =>  '短信验证码必须为数字',
        'code.min'  =>  '短信验证码长度不得低于4',
        'type.require' => '类型必须',
        'type.in' => '类型数值错误',
        'sex.require' => '性别必须',
        'sex.in' => '性别数值错误',
    ];
    
    protected $scene = [
        'send_code' => ['phone_number'],
        'bind' => ['type', 'number', 'flag'],
        'login' => ['phone_number', 'code', 'type'],
        'update_user' => ['username', 'sex'],
    ];
}
