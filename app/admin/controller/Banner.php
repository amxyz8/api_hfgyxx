<?php
namespace app\admin\controller;

use app\admin\validate\Banner as BannerValidate;
use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Banner as BannerService;
use think\facade\Log;
use think\response\Json;

class Banner extends AdminAuthBase
{
    public function index()
    {
        try {
            $res = (new BannerService())->getLists(10);
        } catch (\Exception $e) {
            $res = Arr::getPaginateDefaultData(10);
        }
        
        return Show::success($res);
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
        
        $validate = new BannerValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }
        
        try {
            $result = (new BannerService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/banner/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }
        
        return Show::success($result);
    }
    
    /**
     * 详情
     * @param $id
     * @return Json
     */
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        try {
            $result = (new BannerService())->getNormalBannerById($id);
        } catch (\Exception $e) {
            Log::error('admin/banner/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }
        
        return Show::success($result);
    }
    
    /**
     * 更新数据
     * @param $id
     * @return Json
     */
    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
    
        $id = input("param.id", 0, "intval");
        $data = input('post.');
        
        $validate = new BannerValidate();
        if (!$validate->scene('update')->check($data)) {
            return Show::error($validate->getError());
        }
        try {
            $res = (new BannerService())->update($id, $data);
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
    
        $id = input("param.id", 0, "intval");
        
        try {
            $res = (new BannerService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success();
    }
}
