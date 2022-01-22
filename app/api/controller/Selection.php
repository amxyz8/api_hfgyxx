<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Selection as SelectionServices;
use app\common\services\SelectionOption;
use app\common\services\SelectionResult;
use think\response\Json;

class Selection extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
        $data['target'] = $this->type;
//        $data['end_time'] = date('Y-m-d');
        $date = date("Y-m-d");
        try {
            $list = (new SelectionServices())->getPaginateList($data, 10);
            if ($list['data']) {
                foreach ($list['data'] as &$value) {
                    $isExpired = 0;
                    if ($value['end_time'] < $date) {
                        $isExpired = 1;
                    }
                    $value['is_expired'] = $isExpired;
                }
            }
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }

        return Show::success($list);
    }

    /**
     * 详情
     * @return Json
     */
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        $isSubmit = 0;
        $date = date('Y-m-d');
        $userId = $this->userId;
        try {
            $info = (new \app\common\services\Selection())->getNormalById($id);
            if ($info['end_time'] < $date) {
                return Show::error('此项活动已结束');
            }
            $result = (new SelectionOption())->getPaginateList($id);
            $isSubmit = (new SelectionResult())->isSubmit($userId, $id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        $result['is_submit'] = $isSubmit;

        return Show::success($result);
    }
}
