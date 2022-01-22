<?php

namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\RepairCate as CateServices;

//报修
class Rcate extends AuthBase
{
    public function index()
    {
        $data = [];
        $data['pid'] = 0;
        $list = (new CateServices())->getTreeList($data);
        return Show::success($list);
    }

    public function cate()
    {
        $cate = config('repair.cate');
        return Show::success($cate);
    }
}
