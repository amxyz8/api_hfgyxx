<?php
namespace app\admin\controller;

use app\admin\validate\Department as DepartmentValidate;
use app\common\lib\Show;
use app\common\services\Department as DepartmentService;
use think\facade\Log;
use think\response\Json;

class Department extends AdminAuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
//        $data['pid'] = input('param.pid', 0, 'intval');
//        $list = (new DepartmentService())->getPaginateTreeList($data, 10);
        $list = (new DepartmentService())->getPaginateList($data, 10);

        return Show::success($list);
    }

    /**
     * 无分页列表
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        $list = (new DepartmentService())->getNormalList();

        return Show::success($list);
    }

    /**
     * 有层级分页列表
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function plist()
    {
        $data = [];
        $data['pid'] = input('param.pid', 0, 'intval');
        $list = (new DepartmentService())->getTreeList($data);

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

        $validate = new DepartmentValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new DepartmentService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/department/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage());
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
            $result = (new DepartmentService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/department/read 错误:' . $e->getMessage());
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

        try {
            $res = (new DepartmentService())->update($id, $data);
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
            $res = (new DepartmentService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
