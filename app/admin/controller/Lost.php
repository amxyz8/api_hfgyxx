<?php
namespace app\admin\controller;

use app\api\validate\Lost as LostValidate;
use app\common\lib\Show;
use app\common\services\Lost as LostService;
use app\common\services\Lost as LostServices;
use think\facade\Log;
use think\response\Json;

class Lost extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $title = input('param.title', '', 'trim');
        $time = input('param.create_time', '', 'trim');
        if (!empty($title)) {
            $data['title'] = $title;
        }
        if (!empty($time)) {
            $data['create_time'] = explode(" - ", $time);
        }
        $list = (new LostService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    /**
     * 详情
     * @param $id
     * @return Json
     */
    public function read($id)
    {
        try {
            $result = (new LostServices())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/lost/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 更新数据
     * @return Json
     */
    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input("param.id", 0, "intval");
        $data = input('post.');

        $validate = new LostValidate();
        if (!$validate->check($data)) {
            return Show::error($validate->getError());
        }
        try {
            $res = (new LostServices())->update($id, $data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * 删除数据
     * @return Json
     */
    public function delete()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input("param.id");

        try {
            $res = (new LostServices())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
