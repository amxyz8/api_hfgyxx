<?php
namespace app\admin\controller;

use app\common\lib\Show;

class Logout extends AdminAuthBase
{
    public function index()
    {
        // 删除 redis token 缓存
        $res = cache(config("admin.admin_token_pre").$this->authorization, null);
        if ($res) {
            return Show::success();
        }
        return Show::error();
    }
}
