<?php

namespace app\api\validate;

use think\Validate;

class Lwinner extends Validate
{
    protected $rule = [
        'question_id' => 'require',
        'problem_id' => 'require',
        'option_id' => 'require',
    ];
    
    protected $message = [
        'question_id' => 'question_id不可为空',
        'problem_id' => 'problem_id不可为空',
        'option_id' => 'option_id不可为空',
    ];
    
    protected $scene = [
        'save' => ['question_id', 'problem_id', 'option_id'],
    ];
}
