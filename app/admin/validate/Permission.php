<?php

namespace app\admin\validate;

use think\Validate;

class Permission extends Validate
{
    protected $rule = [
        'name' => 'require',
        'url' => 'require',
        'id' => 'require',
    ];
    
    protected $message = [
        'name' => 'name不可为空',
        'url' => 'url不可为空',
        'id' => 'id不可为空',
    ];
    
    protected $scene = [
        'read' => ['id'],
        'save' => ['name', 'url'],
        'update' => ['name'],
        'delete' => ['name'],
    ];
}
