<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Repair as RepairServices;
use app\api\validate\Repair as RepairValidate;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

//报修
class Repair extends AuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $status = input('param.status', '', 'trim');
        switch ($status) {
            case 'commit'://已提交
                $data['progress_bar'] = [1];
                $data['user_id'] = $this->userId;
                break;
            case 'processing': //处理中
                $data['progress_bar'] = [1,2,3];
                $data['user_id'] = $this->userId;
                break;
            case 'repairing': //维修中
                $data['progress_bar'] = [3];
                $data['repare_id'] = $this->userId;
                break;
            case 'processed': //已处理
                $data['progress_bar'] = [0,4];
                $data['user_id'] = $this->userId;
                break;
            case 'approve': //审批中
                $data['progress_bar'] = [1,2];
                $data['approver_id'] = $this->userId;
                break;
            case 'hasbeen': //已审批 或者 已维修
                if ($this->permission == 1) {
                    $data['approver_id'] = $this->userId;
                    $data['progress_bar'] = [0, 3];
                } elseif ($this->permission == 2) {
                    $data['repare_id'] = $this->userId;
                    $data['progress_bar'] = [4];
                }
                break;
            default:
                $data['progress_bar'] = [0,1,2,3,4];
                $data['user_id'] = $this->userId;
                break;
        }
        try {
            $list = (new RepairServices())->getPaginateList($data, 10);
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }

        return Show::success($list);
    }

    /**
     * 获取维修人列表
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function rlist()
    {
        $number = $this->number;
        $nums = config('repair.numberToExecute')[$number]??[];
        if (!$nums) {
            return Show::success();
        }
        try {
            $list = (new \app\common\services\User())->getNormalUserByNumbers($nums);
        } catch (\Exception $e) {
            $list = [];
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
        $data = input('param.');

        $validate = new RepairValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $data['user_id'] = $this->userId;

        try {
            $result = (new RepairServices())->insertData($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success($result);
    }
    
    /**
     * 详情
     * @param $id
     * @return Json
     */
    public function read($id)
    {
        try {
            $result = (new RepairServices())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('api/repair/read 错误:' . $e->getMessage());
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
        
        $validate = new RepairValidate();
        if (!$validate->scene('update')->check($data)) {
            return Show::error($validate->getError());
        }
        if (isset($data['img_url'])) {
            $data['img_url'] = json_encode($data['img_url']);
        }
        try {
            $res = (new RepairServices())->update($id, $data);
            //发送维修消息模板通知给维修人
            if (isset($data['progress_bar']) && $data['progress_bar']==3 && isset($data['repare_id'])) {
                $repair = (new \app\common\services\User())->getNormalUserById($data['repare_id']);
                (new \app\common\services\Wechat())->Template($repair['openid'], 'repair');
            }
            if (isset($data['rating'])) {
                $info = (new RepairServices())->getNormalById($id);
                $approver = (new \app\common\services\User())->getNormalUserById($info['approver_id']);
                $repair = (new \app\common\services\User())->getNormalUserById($info['repare_id']);
                (new \app\common\services\Wechat())->Template($approver['openid'], 'done');
                (new \app\common\services\Wechat())->Template($repair['openid'], 'done');
            }
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
            $res = (new CateService())->delete($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success();
    }
    
    public function test()
    {
        $orderId = 15;
        Cache::zAdd(config('rediskey.repair_status_key'), time()+config('rediskey.order_expire'), $orderId);
    }
}
