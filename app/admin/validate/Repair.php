<?php

namespace app\admin\validate;

use think\Validate;

class Repair extends Validate
{
    protected $rule = [
        'name' => 'require',
        'repair_cate_id' => 'require',
    ];
    
    protected $message = [
        'name' => 'name不可为空',
        'repair_cate_id' => '请重新选择要导出的分类',
    ];
    
    protected $scene = [
        'save' => ['name'],
        'update' => ['name'],
        'delete' => ['name'],
        'export' => ['repair_cate_id'],
    ];
}
