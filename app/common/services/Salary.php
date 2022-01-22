<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\model\Salary as SalaryModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

class Salary extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new SalaryModel();
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
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getByWhere($data)
    {
        $list = $this->model->getByCondition($data);
        if (!$list) {
            return [];
        }
        $list = current($list);
        return $list;
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
     * @param $data
     * @return \think\Collection
     * @throws \Exception
     */
    public function insertAll($data)
    {
        return $res = $this->model->saveAll($data);
    }
    
    /**
     * @param $data
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getDateGroup($data)
    {
        $list = $this->model->getDateGroup($data);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
    }
}
