<?php

namespace app\api\validate;

use think\Validate;

class Enroll extends Validate
{
    protected $rule = [
        'username' => 'require',
        'mobile' => 'require',
    ];
    
    protected $message = [
        'username' => 'name不可为空',
        'mobile' => 'mobile不可为空',
    ];
    
    protected $scene = [
        'save' => ['username', 'mobile'],
    ];
}
