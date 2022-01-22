<?php
namespace app\admin\controller;

use app\admin\services\AdminUser as AdminUserService;
use app\admin\validate\AdminUser as AdminUserValidate;
use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Rules as RuleService;
use tauthz\facade\Enforcer;
use think\facade\Log;
use think\response\Json;

class User extends AdminAuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
        try {
            $list = (new AdminUserService())->getPaginateLists($data, 10);
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }
        
        return Show::success($list);
    }

    /**
     * @return Json
     */
    public function list()
    {
        $data = input('param.');
        try {
            $list = (new AdminUserService())->getList($data);
        } catch (\Exception $e) {
            $list = [];
        }

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
        
        $validate = new AdminUserValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $data['last_login_ip'] = $this->request->ip();

        try {
            $result = (new AdminUserService())->insertData($data);
            if ($data['rulename'] && is_array($data['rulename'])) {
                foreach ($data['rulename'] as $name) {
                    Enforcer::addRoleForUser($result['id'], $name);
                }
            }
        } catch (\Exception $e) {
            Log::error('admin/user/save 错误:' . $e->getMessage());
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
            $result = (new AdminUserService())->getUserRuleById($id);
        } catch (\Exception $e) {
            Log::error('admin/user/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage());
        }

        return Show::success($result);
    }
    
    /**
     * 详情
     * @return Json
     */
    public function info()
    {
        try {
            $result = (new AdminUserService())->getNormalUserById($this->userId);
        } catch (\Exception $e) {
            Log::error('admin/user/read 错误:' . $e->getMessage());
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

        //更新用户权限
        if (isset($data['rulename'])) {
            if (is_array($data['rulename']) && !empty($data['rulename'])) {
                $ruleData = [
                    'type' => 'g',
                    'uid' => $id,
                ];
                $field = 'id, v1';
                $rule = (new RuleService())->getList($ruleData, $field);
                $rule = array_column($rule, 'v1');
                $diff = array_diff($data['rulename'], $rule);
                if ($diff || count($data['rulename']) != count($rule)) {
                    Enforcer::deleteRolesForUser($id);
                    foreach ($data['rulename'] as $name) {
                        Enforcer::addRoleForUser($id, $name);
                    }
                }
            }

            unset($data['rulename']);
        }

        if (isset($data['password'])) {
            $data['password'] = md5($data['password'].config('admin.password_suffix'));
        }

        try {
            $res = (new AdminUserService())->update($id, $data);
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
        
        $id = input('param.id');
    
        if (1 == $id) {
            return Show::error('该用户不可删除');
        }

        try {
            $res = (new AdminUserService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
