<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\model\Question as QuestionModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

class Question extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new QuestionModel();
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
     * 更新参与人数
     * @param $id
     * @return bool
     */
    public function updateAttendCount($id)
    {
        $this->model->incCount($id, 1);
        return true;
    }
}
