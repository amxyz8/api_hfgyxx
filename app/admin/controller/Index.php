<?php
namespace app\admin\controller;

use app\common\lib\Show;

class Index extends AdminAuthBase
{
    public function index()
    {
        return Show::success();
    }
}
