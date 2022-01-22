<?php


namespace app\common\services;

use app\admin\services\AdminUser;
use app\common\lib\Arr;
use app\common\model\Rules as RulesModel;
use tauthz\facade\Enforcer;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Log;

class Rules extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new RulesModel();
    }

    /**
     * 获取权限列表
     * @param $data
     * @param $field
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList($data, $field)
    {
        try {
            $list = $this->model->getListByType($data, $field);
            $result = $list->toArray();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $result = [];
        }
        return $result;
    }
    
    /**
     * 获取权限列表
     * @param $data
     * @param $field
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getAllRolesByUid($data, $field)
    {
        $adminData = [
            'type' => 'g',
            'uid' => 1,
        ];
        $list = $this->getList($data, $field);
        $options = array_column($list, 'v1');
        $rules = $this->getList($adminData, $field);
//        $roles = Enforcer::getRolesForUser(1);
        foreach ($rules as &$item) {
            $item['is_check'] = 0;
            if (in_array($item['v1'], $options)) {
                $item['is_check'] = 1;
            }
        }
        return $rules;
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
        return $this->model->updateNoTimeById($id, $data);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->model->deleteById($id);
    }
}
