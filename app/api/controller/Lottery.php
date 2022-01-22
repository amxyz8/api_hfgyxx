<?php

namespace app\api\controller;

use app\api\validate\Lottery as LotteryValidate;
use app\common\lib\Arr;
use app\common\lib\Key;
use app\common\lib\Num;
use app\common\lib\Show;
use app\common\services\Lottery as LotteryServices;
use app\common\services\LotteryOption;
use app\common\services\LotteryWinning as WinnerServices;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

//抽奖
class Lottery extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [];
        $data['target'] = $this->type;
        $data['status'] = config('status.mysql.table_normal');
//        $data['end_time'] = date('Y-m-d');
        $date = date("Y-m-d");
        try {
            $list = (new LotteryServices())->getPaginateList($data, 10);
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
        $data = input('param.');

        $validate = new LotteryValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $id = $data['id'];

        try {
            $info = (new LotteryServices())->getNormalById($id);
            $awardsSetting = (new LotteryOption())->getList(['lottery_id' => $id]);
            $rankNameArr = array_column($awardsSetting, 'value');
            $awardsSetting = array_column($awardsSetting, 'count');
            $awardsCount = count($awardsSetting);
            $setting = $rName = [];
            foreach ($awardsSetting as $key => $va) {
                $setting[$awardsCount-$key] = $va;
            }
            foreach ($rankNameArr as $k => $v) {
                $rName[$awardsCount-$k] = $v;
            }
//            $setting = json_decode($info['awards_setting'], true);
            $settingKeys = array_keys($setting);
            $settingValues = array_values($setting);
            $settingCount = array_sum($settingValues);
            $userId = $info['user_id']??0;
            if ($userId != $this->userId) {
                return Show::error('您没有开奖权限!');
            }
            $count = (new WinnerServices())->getCountById($id);
            if ($count >= $settingCount) {
                return Show::error('奖项已抽完!');
            }
            
            if ($count == 0) {
                $count = 1;
            }

            $result = [];
            $i = 0;
            foreach ($setting as $key => $value) {
                $i += $value;
                $result[$key] = $i;
            }

            $rank = current($settingKeys);
            foreach ($result as $k => $v) {
                if ($count == end($result)) {
                    $rank = $k;
                    break;
                }
                if ($count >= $v) {
                    $rank = $k - 1;
                }
            }
            $data['rank'] = $rank;
            $data['rank_name'] = $rName[$rank];

            $result = (new WinnerServices())->insertData($data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
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
        $date = date('Y-m-d');
        try {
            $result = (new LotteryServices())->getNormalById($id);
            if ($result['end_time'] < $date) {
                return Show::error('此项活动已结束');
            }
        } catch (\Exception $e) {
            Log::error('api/lottery/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        $result['is_allow'] = $this->userId == $result['user_id'] ? 1 : 0;

        return Show::success($result);
    }

    /**
     * 取号
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function take()
    {
        $id = input('param.id', 0, 'intval');
        $info = (new \app\common\services\Lottery())->getNormalById($id);
        if (!$info) {
            return Show::error('数据不存在');
        }

        $date = date('Y-m-d');

        if ($date > $info['end_time'] || $date < $info['start_time']) {
            return Show::error('还未到参与时间哦');
        }
        $incr = Cache::incr(Key::LotteryNumIncrKey($id));
        $num = Cache::hSet(Key::LotteryKey($id), $this->userId, $incr);
        $incrNum = Num::fixFourNum($incr);
        $res['number'] = $incrNum;
        return Show::success($res);
    }

    /**
     * 读号
     * @return mixed
     */
    public function get()
    {
        $id = input('param.id', 0, 'intval');
        $num = Cache::hGet(Key::LotteryKey($id), $this->userId);
        if (!$num) {
            return Show::error('您还没有取号');
        }
        $res['number'] = Num::fixFourNum($num);
        return Show::success($res);
    }
    
    /**
     * 读号
     * @return mixed
     */
    public function list()
    {
        $data = input('param.');
        
        $validate = new LotteryValidate();
        if (!$validate->scene('list')->check($data)) {
            return Show::error($validate->getError());
        }
        $res = (new WinnerServices())->getList($data);
        if ($res) {
            $userIds = array_unique(array_column($res, 'user_id'));
            $userList = (new \app\common\services\User())->getUserByIds($userIds);
            $userRes = [];
            if ($userList) {
                foreach ($userList as $value) {
                    $userRes[$value['id']] = [
                        'id' => $value['id'],
                        'username' => $value['username'],
                    ];
                }
            }
            foreach ($res as $k => &$v) {
                $v['username'] = $userRes[$v['user_id']]['username']??'';
                $v['number'] = Num::fixFourNum($v['number']);
            }
        }
        return Show::success($res);
    }
}
