<?php

namespace app\admin\controller;

use app\BaseController;
use think\exception\HttpResponseException;

class AdminBase extends BaseController
{
    public function initialize()
    {
        parent::initialize();
    }
    
    public function show(...$args)
    {
        throw new HttpResponseException(show(...$args));
    }
}
