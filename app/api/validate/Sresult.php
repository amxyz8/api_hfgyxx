<?php

namespace app\api\validate;

use think\Validate;

class Sresult extends Validate
{
    protected $rule = [
        'selection_id' => 'require',
        'option_id' => 'require',
    ];
    
    protected $message = [
        'selection_id' => 'selection_id不可为空',
        'option_id' => 'option_id不可为空',
    ];
    
    protected $scene = [
        'save' => ['selection_id', 'option_id'],
    ];
}
