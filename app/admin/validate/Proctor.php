<?php

namespace app\admin\validate;

use think\Validate;

class Proctor extends Validate
{
    protected $rule = [
        'number' => 'require',
        'date' => 'require',
        'time_period' => 'require',
        'place' => 'require',
        'subject' => 'require',
    ];
    
    protected $message = [
        'number' => 'number不可为空',
        'date' => 'date不可为空',
        'time_period' => 'time_period不可为空',
        'place' => 'place不可为空',
        'subject' => 'subject不可为空',
    ];
    
    protected $scene = [
        'save' => ['number', 'date', 'time_period', 'place', 'subject'],
    ];
}
