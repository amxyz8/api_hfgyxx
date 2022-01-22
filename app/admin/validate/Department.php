<?php

namespace app\admin\validate;

use think\Validate;

class Department extends Validate
{
    protected $rule = [
        'name' => 'require',
    ];
    
    protected $message = [
        'name' => 'name不可为空',
    ];
    
    protected $scene = [
        'save' => ['name'],
    ];
}
