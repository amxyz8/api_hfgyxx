<?php


namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\User as UserServices;

class User extends AuthBase
{
    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $user = (new UserServices())->getNormalUserById($this->userId);
        $res = [];
        if ($user) {
            $res = [
                'id' => $this->userId,
                'type' => $this->type,
                'number' => $this->number,
                'username' => $user['username'],
                'permission' => $this->permission
            ];
        }
        return Show::success($res);
    }
}
