<?php

namespace app\admin\validate;

use think\Validate;

class News extends Validate
{
    protected $rule = [
        'title' => 'require',
        'cate_id' => 'require',
        'content' => 'require',
        'id' => 'require|array',
    ];
    
    protected $message = [
        'name' => 'name不可为空',
        'cate_id' => 'cate_id不可为空',
        'content' => 'content不可为空',
        'id.require' => 'id必须是数组',
        'id.array' => 'id必须是数组',
    ];
    
    protected $scene = [
        'save' => ['title', 'cate_id', 'content'],
        'savev' => ['cate_id'],
        'delete' => ['id'],
    ];
}
