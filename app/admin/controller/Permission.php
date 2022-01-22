<?php
namespace app\admin\controller;

use app\admin\services\AdminUser as AdminUserService;
use app\admin\validate\Permission as PermissionValidate;
use app\common\lib\Show;
use app\common\services\Rules as RuleService;
use tauthz\facade\Enforcer;
use think\response\Json;

class Permission extends AdminAuthBase
{
    /**
     * 获取权限列表
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $data = [
            'type' => 'p',
            'uid' => input('param.uid', 0, 'intval'),
            'name' => '',
        ];

        $field = 'id, v1, v2';

        $list = (new RuleService())->getList($data, $field);

        return Show::success($list);
    }
    
    /**
     * 新增权限
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new PermissionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }
        $admin = (new AdminUserService())->getNormalUserById(1);
        //新增权限
//        Enforcer::addPermissionForUser($admin['username'], $data['name'], $data['url']);
        Enforcer::addPermissionForUser($admin['username'], '微主页', $data['name'], $data['url']);
        return Show::success();
    }
    
    /**
     * 详情
     * @return Json
     */
    public function read()
    {
        $data = input('param.');

        $validate = new PermissionValidate();
        if (!$validate->scene('read')->check($data)) {
            return Show::error($validate->getError());
        }
        try {
            $result = (new RuleService())->getNormalById($data['id']);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
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
            $res = (new RuleService())->update($id, $data);
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
    
        $id = input("param.id", 0, "intval");
        
        try {
            $res = (new RuleService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success();
    }
}
