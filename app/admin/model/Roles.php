<?php


namespace app\admin\model;

use think\db\exception\DbException;

class Roles extends BaseModel
{
    /**
     * 根据id获取用户信息
     * @param $id
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserById($id)
    {
        if (empty($id)) {
            return false;
        }

        $where = [
            'id' => $id
        ];

        return $this->where($where)->find();
    }

    /**
     * genju
     * @param $where
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getListByUsername($where)
    {
        $result = $this->where($where)->select();
        //echo $this->getLastSql();exit;
        return $result;
    }
    
    /**
     * 根据ids获取用户信息
     * @param array $ids
     * @return bool|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserByIds($ids = [])
    {
        if (empty($ids)) {
            return false;
        }
        
        return $this->whereIn('id', $ids)->select();
    }

    /**
     * 获取列表数据
     * @param $where
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getLists($where, $field = '*', $num = 10)
    {
        $order = [
            "id" => "desc"
        ];
        $result = $this->where("status", "<>", config("status.mysql.table_delete"))
            ->where($where)
            ->field($field)
            ->order($order)
            ->paginate($num);
        //echo $this->getLastSql();exit;
        return $result;
    }
}
