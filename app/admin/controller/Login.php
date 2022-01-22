<?php
namespace app\admin\controller;

use app\admin\services\AdminUser as AdminUserServices;
use app\admin\validate\AdminUser as AdminUserValidate;
use app\common\lib\Show;

class Login extends AdminBase
{
    public function index()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');
    
        $validate = new AdminUserValidate();
        if (!$validate->scene('login')->check($data)) {
            return Show::error($validate->getError());
        }
    
        try {
            $result = (new AdminUserServices())->login($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        if ($result) {
            return Show::success($result, '登陆成功');
        }
        return Show::error('登陆失败');
    }
    
    public function md5()
    {
        dump(session(config('admin.session_admin')));
        exit();
        echo md5('admin_gyjj');
    }
    
    public function check()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        
        $username = $this->request->param('username', '', 'trim');
        $password = $this->request->param('password', '', 'trim');
        $captcha = $this->request->param('captcha', '', 'trim');
        
        $data = [
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha,
        ];
        $validate = new AdminUserValidate();
        if (!$validate->check($data)) {
            return Show::error($validate->getError());
        }
        if (empty($username) || empty($password) || empty($captcha)) {
            return Show::error('参数缺失');
        }

        //		if (!captcha_check($captcha)) {
        //			return show(config('status.error'), '验证码错误'.$captcha);
        //		}
        
        try {
            $adminUserModel = new AdminUserModel();
            $adminUser = $adminUserModel->getAdminUserByUsername($username);
            if (empty($adminUser) || $adminUser->status != config('status.mysql.table_normal')) {
                return Show::error('用户不存在');
            }
            $adminUser = $adminUser->toArray();
            if ($adminUser['password'] != md5($password.'_gyjj')) {
                return Show::error('密码错误');
            }
            
            $updateData = [
                'last_login_time' => time(),
                'last_login_ip' => $this->request->ip(),
                'update_time' => time(),
            ];
            
            $res = $adminUserModel->updateById($adminUser['id'], $updateData);
            if (empty($res)) {
                return Show::error('登录失败');
            }
        } catch (\Exception $e) {
            return Show::error('内部异常');
        }
        
        session(config('admin.session_admin'), $adminUser);
    
        return Show::success('登录成功');
    }
}
