<?php

namespace app\api\validate;

use think\Validate;

class TokenGet extends Validate
{
    protected $rule = [
        'code' => 'require',
    ];
    
    protected $message = [
        'code' => 'code不可为空',
    ];
    
    protected $scene = [
        'save' => ['code'],
    ];
}
