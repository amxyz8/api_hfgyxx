<?php

namespace app\admin\validate;

use think\Validate;

class Selection extends Validate
{
    protected $rule = [
        'title' => 'require',
        'selection_id' => 'require',
    ];
    
    protected $message = [
        'title' => 'title不可为空',
        'selection_id' => 'selection_id不可为空',
    ];
    
    protected $scene = [
        'save' => ['title'],
        'update' => ['title'],
        'delete' => ['title'],
        'export' => ['selection_id'],
    ];
}
