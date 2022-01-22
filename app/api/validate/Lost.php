<?php

namespace app\api\validate;

use think\Validate;

class Lost extends Validate
{
    protected $rule = [
        'title' => 'require',
    ];
    
    protected $message = [
        'title' => '标题不可为空',
    ];
    
    protected $scene = [
        'save' => ['title'],
    ];
}
