<?php

namespace app\admin\validate;

use think\Validate;

class Menu extends Validate
{
    protected $rule = [
        'title' => 'require',
        'url' => 'require',
    ];
    
    protected $message = [
        'title' => 'title不可为空',
        'url' => 'url不可为空',
    ];
    
    protected $scene = [
        'save' => ['title', 'url'],
        'update' => ['title'],
        'delete' => ['title'],
    ];
}
