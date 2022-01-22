<?php
namespace app\admin\controller;

use app\common\lib\Show;
use tauthz\facade\Enforcer;
use think\response\Json;

class Sidebar extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $list = config('sidebar.list');
        foreach ($list as $key => $item) {
//    		halt($this->userId, $item['value'], Enforcer::hasRoleForUser($this->userId, $item['value']));
            $bool = Enforcer::hasRoleForUser($this->userId, $item['value']);
            if (!$bool) {
                unset($list[$key]);
            }
        }
        $list = array_values($list);
        return Show::success($list);
    }
}
