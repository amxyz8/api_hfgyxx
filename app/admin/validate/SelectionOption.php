<?php

namespace app\admin\validate;

use think\Validate;

class SelectionOption extends Validate
{
    protected $rule = [
        'selection_id' => 'require',
        'problem_id' => 'require',
        'value' => 'require',
    ];
    
    protected $message = [
        'selection_id' => 'selection_id不可为空',
        'value' => 'value不可为空',
    ];
    
    protected $scene = [
        'index' => ['selection_id'],
        'save' => ['selection_id', 'value'],
        'update' => ['value'],
    ];
}
