<?php

namespace app\admin\validate;

use think\Validate;

class AdminUser extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require',
    ];
    
    protected $message = [
        'username' => 'username不可为空',
        'password' => 'password不可为空',
    ];
    
    protected $scene = [
        'login' => ['username', 'password'],
        'save' => ['username', 'password'],
    ];
}
