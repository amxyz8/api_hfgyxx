<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\model\Lost as LostModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Cache;
use think\facade\Log;

class Lost extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new LostModel();
    }

    /**
     * 插入数据
     * @param $data
     * @return array
     * @throws Exception
     */
    public function insertData($data)
    {
        try {
            $id = $this->add($data);
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
                $uids = array_unique(array_column($result['data'], 'user_id'));
                if ($uids) {
                    $users = (new User())->getUserByIds($uids);
                    $userNames = array_column($users, 'username', 'id');
                }
                foreach ($result['data'] as &$datum) {
                    $datum['user_name'] = $userNames[$datum['user_id']]??'';
                    $datum['img_url'] = json_decode($datum['img_url'], true);
                }
            }
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
        $info = $res->toArray();
        $info['img_url'] = json_decode($res['img_url']);
        return $info;
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
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function lostCommand()
    {
        $result = Cache::zRangeByScore('lost_status', 0, time(), ['limit' => [0, 1]]);
        //		$result = Cache::store('redis')->zRangeByScore("order_status", 0, time(), ['limit' => [0, 1]]);
        
        if (empty($result) || empty($result[0])) {
            return false;
        }
        
        try {
            $delRedis = Cache::zRem('lost_status', $result[0]);
            //			$delRedis = Cache::store('redis')->zRem("order_status", $result[0]);
        } catch (\Exception $e) {
            // 记录日志
            Log::error("失物招领id:{$result[0]}-" . $e->getMessage());
            $delRedis = "";
        }
        if ($delRedis) {
            $this->update($result[0], ['status' => 2]);
            echo "失物招领id:{$result[0]}更新成功";
        } else {
            return false;
        }
        
        return true;
    }
}
