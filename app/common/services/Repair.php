<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\model\Repair as RepairModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Cache;

class Repair extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new RepairModel();
    }

    /**
     * 插入数据
     * @param $data
     * @return array
     * @throws Exception
     */
    public function insertData($data)
    {
        if (isset($data['img_url'])) {
            $data['img_url'] = json_encode($data['img_url']);
        }

        //获取审批人id
        $cate = (new RepairCate())->getNormalById($data['repair_cate_id']);
        $pid = $cate['pid']??1;
        $config = config('repair.pidBindNumber');
        $number = $config[$pid];
        $approver = (new User())->getNormalUserByNumber($number);
        $data['approver_id'] = $approver['id']??0;

        $noticeOpenId = $approver['openid']??'';

        try {
            $id = $this->add($data);
            //发送微信消息通知
            if ($noticeOpenId) {
                (new Wechat())->Template($noticeOpenId, 'approver');
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
        return ['id' => $id];
    }

    /**
     * @param $data
     * @return array
     */
    public function getList($data)
    {
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getList($likeKeys, $data);
            $result = $list->toArray();
            if ($result) {
                $uids = array_unique(array_column($result, 'user_id'));
                $aids = array_unique(array_column($result, 'approver_id'));
                $rids = array_unique(array_column($result, 'repare_id'));
                $cateIds = array_unique(array_column($result, 'repair_cate_id'));
                $uids = array_unique(array_merge($uids, $aids, $rids));
                if ($uids) {
                    $users = (new User())->getUserByIds($uids);
                    $userNames = array_column($users, 'username', 'id');
                }
                if ($cateIds) {
                    $cates = (new RepairCate())->getNormalByIds($cateIds);
                    $cateNames = array_column($cates, 'name', 'id');
                }
                foreach ($result as &$datum) {
                    $datum['username'] = $userNames[$datum['user_id']]??'';
                    $datum['approvername'] = $userNames[$datum['approver_id']]??'';
                    $datum['reparename'] = $userNames[$datum['repare_id']]??'';
                    $datum['catename'] = $cateNames[$datum['repair_cate_id']]??'';
                    $datum['status'] = RepairModel::$statusMap[$datum['status']];
                    $datum['img_url'] = json_decode($datum['img_url'], true);
                }
            }
        } catch (\Exception $e) {
            $result = [];
        }
        return $result;
    }
    
    /**
     * @param $data
     * @return array
     */
    public function getExportList($data)
    {
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getExportList($likeKeys, $data);
            $result = $list->toArray();
        } catch (\Exception $e) {
            $result = [];
        }
        return $result;
    }

    /**
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalList()
    {
        $field = 'id, name';
        $list = $this->model->getNormalList($field);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
    }

    /**
     * @param $data
     * @param int $num
     * @return array
     * @throws DbException
     */
    public function getPaginateList($data, $num = 10)
    {
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getPaginateList($likeKeys, $data, $field = '*', $num);
            $result = $list->toArray();
            if ($result['data']) {
                $day = 0;
                $uids = array_unique(array_column($result['data'], 'user_id'));
                $aids = array_unique(array_column($result['data'], 'approver_id'));
                $rids = array_unique(array_column($result['data'], 'repare_id'));
                $cateIds = array_unique(array_column($result['data'], 'repair_cate_id'));
                $uids = array_unique(array_merge($uids, $aids, $rids));
                if ($uids) {
                    $users = (new User())->getUserByIds($uids);
                    $userNames = array_column($users, 'username', 'id');
                }
                if ($cateIds) {
                    $cates = (new RepairCate())->getNormalByIds($cateIds);
                    $cateNames = array_column($cates, 'name', 'id');
                }
                foreach ($result['data'] as &$datum) {
                    $day = floor((strtotime($datum['update_time'])-strtotime($datum['create_time']))/86400);
                    $datum['username'] = $userNames[$datum['user_id']]??'';
                    $datum['approvername'] = $userNames[$datum['approver_id']]??'';
                    $datum['reparename'] = $userNames[$datum['repare_id']]??'';
                    $datum['catename'] = $cateNames[$datum['repair_cate_id']]??'';
                    $datum['status'] = RepairModel::$statusMap[$datum['status']];
                    $datum['img_url'] = json_decode($datum['img_url'], true);
                    $datum['day'] = $day;
                }
            }
        } catch (\Exception $e) {
            $result = Arr::getPaginateDefaultData($num);
        }
        return $result;
    }
    
    /**
     * @param $id
     * @param $data
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function update($id, $data)
    {
        $res = $this->getNormalById($id);
        if (!$res) {
            throw new Exception("数据不存在");
        }
        return $this->model->updateById($id, $data);
    }
    
    /**
     * @param $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalById($id)
    {
        $res = $this->model->getById($id);
        if (!$res) {
            return [];
        }
        $info = $res->toArray();
        $info['img_url'] = json_decode($res['img_url']);
        return $info;
    }

    /**
     * @param $id
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function delete($id)
    {
        $cate = $this->getNormalById($id);
        if (!$cate) {
            throw new Exception("数据不存在");
        }
        
        $data = [
            'status' => config('status.mysql.table_delete')
        ];
        
        return $this->model->updateById($id, $data);
    }
    
    /**
     * @param $orderId
     * @param $time
     * @return bool
     */
    public function testCommond()
    {
        $result = Cache::zRangeByScore('order_status', 0, time(), ['limit' => [0, 1]]);
        //		$result = Cache::store('redis')->zRangeByScore("order_status", 0, time(), ['limit' => [0, 1]]);
        
        if (empty($result) || empty($result[0])) {
            return false;
        }
        
        try {
            $delRedis = Cache::zRem('order_status', $result[0]);
            //			$delRedis = Cache::store('redis')->zRem("order_status", $result[0]);
        } catch (\Exception $e) {
            // 记录日志
            $delRedis = "";
        }
        if ($delRedis) {
            echo "订单id:{$result[0]}在规定时间内没有完成支付 我们判定为无效订单删除".PHP_EOL;
        } else {
            return false;
        }
        
        return true;
    }
}
