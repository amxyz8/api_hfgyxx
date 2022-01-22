<?php

namespace app\api\validate;

use think\Validate;

class Proctor extends Validate
{
    protected $rule = [
        'date' => 'require',
    ];
    
    protected $message = [
        'date' => 'date不可为空',
    ];
    
    protected $scene = [
        'index' => ['date'],
    ];
}
