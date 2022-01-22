<?php

namespace app\api\validate;

use think\Validate;

class Video extends Validate
{
    protected $rule = [
        'id' => 'require',
    ];
    
    protected $message = [
        'id' => 'id不可为空',
    ];
    
    protected $scene = [
        'read' => ['id'],
    ];
}
