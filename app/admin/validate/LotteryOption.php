<?php

namespace app\admin\validate;

use think\Validate;

class LotteryOption extends Validate
{
    protected $rule = [
        'lottery_id' => 'require',
        'value' => 'require',
        'count' => 'require',
    ];
    
    protected $message = [
        'lottery_id' => 'lottery_id不可为空',
        'value' => 'value不可为空',
        'count' => 'count不可为空',
    ];
    
    protected $scene = [
        'index' => ['lottery_id'],
        'save' => ['lottery_id', 'value', 'count'],
    ];
}
