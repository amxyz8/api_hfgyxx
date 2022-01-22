<?php

namespace app\api\validate;

use think\Validate;

class Problem extends Validate
{
    protected $rule = [
        'question_id' => 'require',
        'problem_id' => 'require',
    ];
    
    protected $message = [
        'question_id' => 'question_id不可为空',
        'problem_id' => 'problem_id不可为空',
    ];
    
    protected $scene = [
        'index' => ['question_id'],
        'save' => ['question_id', 'problem_id'],
    ];
}
