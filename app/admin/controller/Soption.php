<?php
namespace app\admin\controller;

use app\admin\validate\SelectionOption as SelectionOptionValidate;
use app\common\lib\Show;
use app\common\services\SelectionOption as SelectionOptionService;
use think\facade\Log;
use think\response\Json;

class Soption extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = input('param.');
        $validate = new SelectionOptionValidate();
        if (!$validate->scene('index')->check($data)) {
            return Show::error($validate->getError());
        }
        $sid = $data['selection_id'];
        $list = (new SelectionOptionService())->getPaginateList($sid, 10);
        
        return Show::success($list);
    }

    /**
     * 新增
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new SelectionOptionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new SelectionOptionService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/soption/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 详情
     * @return Json
     */
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        try {
            $result = (new SelectionOptionService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/soption/read 错误:' . $e->getMessage());
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

        $id = input('param.id', 0, 'intval');
        $data = input('post.');
//        $data = $this->request->only(['is_hot', 'is_top', 'title', 'content'], 'post');

        try {
            $res = (new SelectionOptionService())->update($id, $data);
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
            $res = (new SelectionOptionService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
