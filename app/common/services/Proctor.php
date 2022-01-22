<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\model\Proctor as ProctorModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

//老师监考
class Proctor extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new ProctorModel();
    }

    /**
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalList()
    {
        $list = $this->model->getNormalList();
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
    }

    /**
     * @param $data
     * @return bool|\think\Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getListByDateAndNumber($data)
    {
        $list = $this->model->getListByDateAndNumber($data);
        return $list;
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
     * @param $data
     * @return \think\Collection
     * @throws \Exception
     */
    public function insertAll($data)
    {
        return $res = $this->model->saveAll($data);
    }
}
