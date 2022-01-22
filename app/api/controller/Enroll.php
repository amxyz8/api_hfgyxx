<?php

namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\Enroll as EnrollServices;
use app\api\validate\Enroll as EnrollValidate;
use think\response\Json;

class Enroll extends ApiBase
{
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
        
        $validate = new EnrollValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new EnrollServices())->insertData($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success($result);
    }
}
