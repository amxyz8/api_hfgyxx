<?php
namespace app\admin\controller;

use app\common\lib\Show;
use app\common\services\Enroll as EnrollService;
use think\response\Json;

class Enroll extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $list = (new EnrollService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }
}
