<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\model\RepairCate as RepairCateModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

class RepairCate extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new RepairCateModel();
    }

    /**
     * 获取列表数据
     * @param $data
     * @return array
     */
    public function getTreeList($data)
    {
        $field = 'id, name, pid';
        try {
            $list = $this->model->getList($data, $field);
            $result = $list->toArray();
            $pids = array_column($result, "id");
            if ($pids) {
                $idCountResult = $this->model->getChildListInPids(['pid' => $pids]);
                $idCountResult = $idCountResult->toArray();
                $idCounts = [];
                foreach ($idCountResult as $countResult) {
                    $idCounts[$countResult['pid']][] = $countResult;
                }
            }

            if ($result) {
                foreach ($result as $k => $value) {
                    $result[$k]['child'] = $idCounts[$value['id']] ?? [];
                }
            }
        } catch (\Exception $e) {
            $result = [];
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
        return $info;
    }
    
    /**
     * @param $ids
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalByIds($ids)
    {
        $res = $this->model->getByIds($ids);
        if (!$res) {
            return [];
        }
        return $res;
    }
}
