<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Question as QuestionServices;
use app\api\validate\Qresult as QuestionValidate;
use think\response\Json;

//问卷调查
class Question extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
//        $data['end_time'] = date('Y-m-d');
        $date = date("Y-m-d");
        try {
            $list = (new QuestionServices())->getPaginateList($data, 10);
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
     * 新增
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');
        
        $validate = new QuestionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new QuestionServices())->insertData($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success($result);
    }
}
