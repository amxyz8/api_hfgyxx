<?php

namespace app\api\controller;

use app\api\validate\Proctor as ProctorValidate;
use app\common\services\Proctor as ProctorServices;
use app\common\services\User as UserServices;
use app\common\lib\Show;
use think\response\Json;

//监考
class Proctor extends AuthBase
{

    /**
     * @return Json
     */
    public function index()
    {
        $data['date'] = input('param.date', '', 'trim');
        $list = [];
//
//        $validate = new ProctorValidate();
//        if (!$validate->scene('index')->check($data)) {
//            return Show::error($validate->getError());
//        }

        try {
            $user = (new UserServices())->getNormalUserById($this->userId);
            $data['number'] = $user['number'];
            $list = (new ProctorServices())->getListByDateAndNumber($data);
        } catch (\Exception $e) {
            return Show::success($list);
        }

        return Show::success($list);
    }

    /**
     * @return Json
     */
    public function group()
    {
        $data['date'] = date('Y-m-d', time());
//        halt(date('Y-m-d', 1603382400));
        try {
            $user = (new UserServices())->getNormalUserById($this->userId);
            $data['number'] = $user['number']??'';
            $list = (new ProctorServices())->getDateGroup($data);
        } catch (\Exception $e) {
            $list = [];
        }

        return Show::success($list);
    }
}
