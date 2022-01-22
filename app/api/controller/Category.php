<?php


namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Category as CategoryServices;
use app\common\services\Category as CateService;
use app\api\validate\Category as CateValidate;
use think\facade\Log;
use think\response\Json;

class Category extends ApiBase
{
    public function index()
    {
        $pid = input("param.pid", 0, "intval");
        $data = [
            "pid" => $pid,
            "is_show" => 1,
        ];
        try {
            $categorys = (new CategoryServices())->getLists($data, 20);
        } catch (\Exception $e) {
            $categorys = Arr::getPaginateDefaultData(10);
            ;
        }
    
        return Show::success($categorys);
    }

    /**
     * @return Json
     */
    public function create()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');
        
        $validate = new CateValidate();
        if (!$validate->scene('register')->check($data)) {
            return Show::error($validate->getError(), config('status.name_not_null'));
        }
        
        try {
            $result = (new CateService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('api/category/create 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }
        
        return Show::success($result);
    }
    
    public function read($id)
    {
    }
}
