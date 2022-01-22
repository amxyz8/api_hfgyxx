<?php
namespace app\admin\controller;

use app\admin\validate\Rule as RuleValidate;
use app\common\lib\Show;
use app\common\services\Rules as RuleService;
use tauthz\facade\Enforcer;
use think\response\Json;

class Rule extends AdminAuthBase
{
    /**
     * 获取所有角色
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $data = [
            'type' => 'g',
            'uid' => input('param.uid', 1, 'intval'),
        ];

        $field = 'id, v1';

        $list = (new RuleService())->getList($data, $field);

        return Show::success($list);
    }

    /**
     * 新增角色
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new RuleValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }
        //后台管理员必须保证id=1的账号存在, 默认超级管理员
//        $admin = (new AdminUser())->getNormalUserById(1);
//        $user = (new AdminUser())->getNormalUserById($data['id']);
        Enforcer::addRoleForUser(1, $data['name']);
//        Enforcer::addRoleForUser($user['username'], $data['name'], 'index');

        return Show::success();
    }
    
    /**
     * 给用户赋予角色
     * @return Json
     */
    public function give()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new RuleValidate();
        if (!$validate->scene('give')->check($data)) {
            return Show::error($validate->getError());
        }
        Enforcer::addRoleForUser($data['user_id'], $data['name']);

        return Show::success();
    }
    
    /**
     * 取消用户角色
     * @return Json
     */
    public function cancel()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');
        
        $validate = new RuleValidate();
        if (!$validate->scene('cancel')->check($data)) {
            return Show::error($validate->getError());
        }
        Enforcer::deleteRoleForUser($data['user_id'], $data['name']);
        
        return Show::success();
    }
    
    /**
     * 取消用户所有角色
     * @return Json
     */
    public function acancel()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');
        
        $validate = new RuleValidate();
        if (!$validate->scene('cancelAll')->check($data)) {
            return Show::error($validate->getError());
        }
        Enforcer::deleteRolesForUser($data['user_id']);
        
        return Show::success();
    }
    
    /**
     * 获取用户所有角色
     * @return Json
     */
    public function getall()
    {
        $data = [
            'type' => 'g',
            'uid' => input('param.user_id', 1, 'intval'),
        ];
        
        $field = 'id, v1';
        
        $list = (new RuleService())->getAllRolesByUid($data, $field);
        
        return Show::success($list);
    }
}
