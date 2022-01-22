<?php

namespace app\admin\validate;

use think\Validate;

class Banner extends Validate
{
    protected $rule = [
        'img_url' => 'require',
    ];
    
    protected $message = [
        'img_url' => 'img_url不可为空',
    ];
    
    protected $scene = [
        'save' => ['img_url'],
        'update' => ['img_url'],
    ];
}
