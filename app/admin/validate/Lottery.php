<?php

namespace app\admin\validate;

use think\Validate;

class Lottery extends Validate
{
    protected $rule = [
        'id' => 'require',
        'title' => 'require',
    ];
    
    protected $message = [
        'id' => 'id不可为空',
        'title' => 'title不可为空',
    ];
    
    protected $scene = [
        'save' => ['title'],
        'update' => ['title'],
        'delete' => ['title'],
        'export' => ['id'],
    ];
}
