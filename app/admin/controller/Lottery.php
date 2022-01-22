<?php
namespace app\admin\controller;

use app\admin\services\AdminUser;
use app\admin\validate\Lottery as LotteryValidate;
use app\common\lib\Excel as ExcelLib;
use app\common\lib\Show;
use app\common\services\DepartmentUser;
use app\common\services\Lottery as LotteryService;
use app\common\services\LotteryWinning as WinnerService;
use think\facade\Cache;
use think\facade\Log;
use think\response\Json;

class Lottery extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $title = input('param.title', '', 'trim');
        $status = input('param.status', '', 'trim');
        if (!empty($title)) {
            $data['title'] = $title;
        }
        if (!empty($status)) {
            $data['status'] = $status;
        }

        $list = (new LotteryService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    /**
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $data = input('post.');

        $validate = new LotteryValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $departUserInfo = (new DepartmentUser())->getNormalById($data['user_id']);
        $userInfo = (new \app\common\services\User())->getNormalUserByNumber($departUserInfo['number']);
        if (!$userInfo) {
            return Show::error('先让此用户在公众号端进行绑定!');
        }
        $data['user_id'] = $userInfo['id']??0;
        $data['start_time'] = $data['start_time'] . '00:00:00';
        $data['end_time'] = $data['end_time'] . '23:59:59';

        try {
            $result = (new LotteryService())->insertData($data);
            Cache::zAdd(config("rediskey.lottery_status_key"), strtotime($data['end_time']), $result['id']);
        } catch (\Exception $e) {
            Log::error('admin/lottery/save 错误:' . $e->getMessage());
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
        try {
            $result = (new LotteryService())->getNormalById($id);
            $user = (new \app\common\services\User())->getNormalUserById($result['user_id']);
            $departUser = (new DepartmentUser())->getByNumber($user['number']);
            $result['department_id'] = $departUser['department_id'];
            $result['user_id'] = $departUser['id'];
        } catch (\Exception $e) {
            Log::error('admin/lottery/read 错误:' . $e->getMessage());
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
        
        if (!$data['user_id']) {
            return Show::error('请设置抽奖人');
        }
    
        $departUser = (new DepartmentUser())->getNormalById($data['user_id']);
        $user = (new \app\common\services\User())->getNormalUserByNumber($departUser['number']);
        $data['user_id'] = $user['id'];

        try {
            $res = (new LotteryService())->update($id, $data);
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
            $res = (new LotteryService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * @return Json
     */
    public function export()
    {
        $input = input('param.');

        $validate = new LotteryValidate();
        if (!$validate->scene('export')->check($input)) {
            return Show::error($validate->getError());
        }

        $where = [
            'lottery_id' => $input['id']
        ];

        // 查询要导出的数据
        $winList = (new WinnerService())->getList($where);
        if (!$winList) {
            return Show::error('没有数据可导出');
        }
        $userIds = array_unique(array_column($winList, 'user_id'));
        $userList = (new \app\common\services\User())->getUserByIds($userIds);
        $lotteryInfo = (new LotteryService())->getNormalById($input['id']);
        $userRes = [];
        if ($userList) {
            foreach ($userList as $value) {
                $userRes[$value['id']] = [
                    'id' => $value['id'],
                    'username' => $value['username'],
                    'number' => $value['number'],
                ];
            }
        }

        $data = [];

        foreach ($winList as $k => $v) {
            $data[$k]['title']=$lotteryInfo['title'];
            $data[$k]['username']=$userRes[$v['user_id']]['username']??'';
            $data[$k]['number']=$userRes[$v['user_id']]['number']??'';
            $data[$k]['rank_name']=$v['rank_name']??'';
        }
//        $filename = "报修数据文档".date('YmdHis');
        $filename = "中奖结果数据";
        $header = [
            ['column' => 'title', 'name' => '活动名称', 'width' => 15],
            ['column' => 'username', 'name' => '姓名', 'width' => 15],
            ['column' => 'number', 'name' => '职工号', 'width' => 15],
            ['column' => 'rank_name', 'name' => '奖项', 'width' => 15],
        ];
        $download_url=(new ExcelLib())->exportSheelExcel($data, $header, $filename);//获取下载链接

        if ($download_url) {
            return Show::success(['url' => $download_url]);
        }

        return Show::error();
    }
}
