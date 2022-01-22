<?php

namespace app\admin\validate;

use think\Validate;

class QuestionProblem extends Validate
{
    protected $rule = [
        'question_id' => 'require',
        'title' => 'require',
    ];
    
    protected $message = [
        'question_id' => 'question_id不可为空',
        'title' => 'title不可为空',
    ];
    
    protected $scene = [
        'index' => ['question_id'],
        'save' => ['title', 'question_id'],
        'update' => ['title'],
    ];
}
