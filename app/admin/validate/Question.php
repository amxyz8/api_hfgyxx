<?php

namespace app\admin\validate;

use think\Validate;

class Question extends Validate
{
    protected $rule = [
        'title' => 'require',
        'question_id' => 'require',
    ];
    
    protected $message = [
        'title' => 'title不可为空',
        'question_id' => 'question_id不可为空',
    ];
    
    protected $scene = [
        'save' => ['title'],
        'update' => ['title'],
        'delete' => ['title'],
        'export' => ['question_id'],
    ];
}
