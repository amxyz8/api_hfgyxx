<?php
namespace app\admin\controller;

use app\admin\validate\LotteryOption as LotteryOptionValidate;
use app\common\lib\Show;
use app\common\services\LotteryOption as LotteryOptionService;
use think\facade\Log;
use think\response\Json;

class Loption extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $input = input('param.');
        $validate = new LotteryOptionValidate();
        if (!$validate->scene('index')->check($input)) {
            return Show::error($validate->getError());
        }
        $data['lottery_id'] = $input['lottery_id'];
        $list = (new LotteryOptionService())->getPaginateList($data, 10);
        
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

        $validate = new LotteryOptionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new LotteryOptionService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/loption/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 详情
     * @return Json
     */
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        try {
            $result = (new LotteryOptionService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/loption/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 更新数据
     * @return Json
     */
    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input('param.id', 0, 'intval');
        $data = input('post.');
//        $data = $this->request->only(['is_hot', 'is_top', 'title', 'content'], 'post');

        try {
            $res = (new LotteryOptionService())->update($id, $data);
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

        $id = input("param.id");

        try {
            $res = (new LotteryOptionService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
