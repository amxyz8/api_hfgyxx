<?php
namespace app\admin\controller;

use app\admin\validate\DepartmentUser as DepartmentUserValidate;
use app\common\lib\Show;
use app\common\services\Department as DepartmentService;
use app\common\services\DepartmentUser as DepartmentUserService;
use think\facade\Log;
use think\response\Json;

class Duser extends AdminAuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
        $list = (new DepartmentUserService())->getPaginateList($data, 10);

        if ($list['data']) {
            $dids = array_unique(array_column($list['data'], 'department_id'));
            $depart = (new DepartmentService())->getListByIds($dids);
            $names = array_column($depart, 'name', 'id');
            foreach ($list['data'] as &$value) {
                $value['departname'] = $names[$value['department_id']]??'';
            }
        }

        return Show::success($list);
    }

    /**
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        $data = [];
        $did = input('param.department_id', 0, 'intval');
        $data['department_id'] = $did;
        $list = (new DepartmentUserService())->getListByData($data);

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

        $validate = new DepartmentUserValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $user = (new \app\common\services\User())->getNormalUserByNumber($data['number']);
        if (!$user) {
            return Show::error('先让此用户在公众号端进行绑定!');
        }
        $data['username'] = $user['username']??'';

        try {
            $result = (new DepartmentUserService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/duser/save 错误:' . $e->getMessage());
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
            $result = (new DepartmentUserService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/duser/read 错误:' . $e->getMessage());
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
            $res = (new DepartmentUserService())->update($id, $data);
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
            $res = (new DepartmentUserService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
