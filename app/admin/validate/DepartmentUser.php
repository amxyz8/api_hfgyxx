<?php

namespace app\admin\validate;

use think\Validate;

class DepartmentUser extends Validate
{
    protected $rule = [
        'department_id' => 'require',
        'number' => 'require',
    ];
    
    protected $message = [
        'department_id' => 'department_id不可为空',
        'number' => 'number不可为空',
    ];
    
    protected $scene = [
        'save' => ['number', 'department_id'],
    ];
}
