<?php

namespace app\api\validate;

use think\Validate;

class Lottery extends Validate
{
    protected $rule = [
        'id' => 'require',
        'lottery_id' => 'require',
    ];
    
    protected $message = [
        'id' => 'id不可为空',
        'lottery_id' => 'lottery_id不可为空',
    ];
    
    protected $scene = [
        'save' => ['id'],
        'list' => ['lottery_id'],
    ];
}
