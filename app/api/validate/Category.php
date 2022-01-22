<?php

namespace app\api\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'name' => 'require',
    ];
    
    protected $message = [
        'name' => 'name不可为空',
    ];
    
    protected $scene = [
        'register' => ['name'],
    ];
}
