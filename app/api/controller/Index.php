<?php
namespace app\api\controller;

use app\BaseController;
use app\common\lib\Num;
use app\common\lib\Show;

class Index extends BaseController
{
    public function index()
    {
        return Show::success();
    }
}
