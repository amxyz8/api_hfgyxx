<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\lib\Key;
use app\common\model\Lottery as LotteryModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Cache;
use think\facade\Log;

class Lottery extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new LotteryModel();
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
        } catch (\Exception $e) {
            $result = Arr::getPaginateDefaultData($num);
        }
        return $result;
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
        return $res->toArray();
    }

    /**
     * 返回正常数据
     * @param $title
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalByTitle($title)
    {
        $res = $this->model->getNewsByTitle($title);
        if (!$res || $res->status != config("status.mysql.table_normal")) {
            return [];
        }
        return $res->toArray();
    }

    /**
     * 插入数据
     * @param $data
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function insertData($data)
    {
        $res = $this->getNormalByTitle($data['title']);
        if ($res) {
            throw new Exception("标题不可重复");
        }
        
//        $data['awards_setting'] = json_encode($data['awards_setting']);

        try {
            $id = $this->add($data);
        } catch (\Exception $e) {
            throw new Exception('数据库内部异常');
        }
        $result = [
            'id' => $id
        ];
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
     * @param $data
     * @return \think\Collection
     * @throws \Exception
     */
    public function insertAll($data)
    {
        return $res = $this->model->saveAll($data);
    }

    /**
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function lotteryCommand()
    {
        $result = Cache::zRangeByScore('lottery_status', 0, time(), ['limit' => [0, 1]]);
        //		$result = Cache::store('redis')->zRangeByScore("order_status", 0, time(), ['limit' => [0, 1]]);

        if (empty($result) || empty($result[0])) {
            return false;
        }

        try {
            $delRedis = Cache::zRem('lottery_status', $result[0]);
            Cache::del(Key::LotteryNumIncrKey($result[0]));
            $this->deleteRedis($result[0]);
            //$delRedis = Cache::store('redis')->zRem("order_status", $result[0]);
        } catch (\Exception $e) {
            // 记录日志
            Log::error("抽奖活动id:{$result[0]}-" . $e->getMessage());
            $delRedis = "";
        }
        if ($delRedis) {
            $this->update($result[0], ['status' => 2]);
            echo "抽奖活动id:{$result[0]}更新成功";
        } else {
            return false;
        }

        return true;
    }

    /**
     * 删除抽奖rediskey
     * @param $lotteryId
     * @return bool
     */
    public function deleteRedis($lotteryId)
    {
        $keys = Cache::hGetAll(Key::LotteryKey($lotteryId));
        $ids = array_keys($keys);
        try {
            // ... 是PHP提供一个特性 可变参数
            $res = Cache::hDel(Key::LotteryKey($lotteryId), ...$ids);
        } catch (\Exception $e) {
            return false;
        }
        return $res;
    }
}
