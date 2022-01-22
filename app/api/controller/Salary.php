<?php

namespace app\api\controller;

use app\api\validate\Salary as SalaryValidate;
use app\common\lib\Show;
use app\common\services\Salary as SalaryServices;
use think\response\Json;

//抽奖
class Salary extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
        $input = input('param.');
    
        $validate = new SalaryValidate();
        if (!$validate->scene('index')->check($input)) {
            return Show::error($validate->getError());
        }
    
        $data['number'] = $this->number;
        $data['month'] = $input['date'];
        
        try {
            $list = (new SalaryServices())->getByWhere($data);
        } catch (\Exception $e) {
            $list = [];
        }

        return Show::success($list);
    }
    
    /**
     * @return Json
     */
    public function group()
    {
        $data = [];
        $data['number'] = $this->number;
        try {
            $list = (new SalaryServices())->getDateGroup($data);
        } catch (\Exception $e) {
            halt($e->getMessage());
            $list = [];
        }
        
        return Show::success($list);
    }
}
