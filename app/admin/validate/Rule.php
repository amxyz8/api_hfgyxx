<?php

namespace app\admin\validate;

use think\Validate;

class Rule extends Validate
{
    protected $rule = [
        'user_id' => 'require',
        'name' => 'require',
    ];
    
    protected $message = [
        'user_id' => 'user_id不可为空',
        'name' => 'name不可为空',
    ];
    
    protected $scene = [
        'save' => ['name'],
        'give' => ['user_id', 'name'],
        'cancel' => ['user_id', 'name'],
        'cancelAll' => ['user_id'],
        'update' => ['name'],
        'delete' => ['name'],
    ];
}
