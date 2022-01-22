<?php

namespace app\admin\validate;

use think\Validate;

class QuestionOption extends Validate
{
    protected $rule = [
        'question_id' => 'require',
        'problem_id' => 'require',
        'value' => 'require',
    ];
    
    protected $message = [
        'question_id' => 'question_id不可为空',
        'problem_id' => 'problem_id不可为空',
        'value' => 'value不可为空',
    ];
    
    protected $scene = [
        'index' => ['question_id', 'problem_id'],
        'save' => ['question_id', 'problem_id', 'value'],
        'update' => ['value'],
    ];
}
