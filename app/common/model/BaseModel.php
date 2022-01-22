<?php


namespace app\common\model;

use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;

    public function updateById($id, $data)
    {
        $data['update_time'] = time();
        if (isset($data['start_time'])) {
            $data['start_time'] = strtotime($data['start_time'] . '00:00:00');
        }

        if (isset($data['end_time'])) {
            $data['end_time'] = strtotime($data['end_time'] . '23:59:59');
        }
        return $this->where(["id" => $id])->save($data);
    }

    public function updateNoTimeById($id, $data)
    {
        return $this->where(["id" => $id])->save($data);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteById($id)
    {
        return $this->where('id', '=', $id)->delete();
    }

    /**
     * 根据条件查询
     * @param array $condition
     * @param array $order
     * @return bool|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getByCondition($condition = [], $order = ["id" => "desc"])
    {
        if (!$condition || !is_array($condition)) {
            return false;
        }
        $result = $this->where($condition)
            ->order($order)
            ->select()
            ->toArray();

//        echo $this->getLastSql();exit;
        return $result;
    }
}
