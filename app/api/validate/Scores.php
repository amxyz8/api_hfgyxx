<?php

namespace app\api\validate;

use think\Validate;

class Scores extends Validate
{
    protected $rule = [
        'xq' => 'require',
        'xn' => 'require',
    ];
    
    protected $message = [
        'xq' => 'xq不可为空',
        'xn' => 'xn不可为空',
    ];
    
    protected $scene = [
        'index' => ['xq', 'xn'],
    ];
}
