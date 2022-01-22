<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Enroll extends BaseModel
{
    protected $hidden = [
      'update_time',
      'delete_time'
    ];

    /**
     * @param $id
     * @return array|bool|\think\Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        $res = $this->find($id);
        return $res;
    }

    /**
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalList($field = "*")
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];

        $order = [
            'id' => 'desc'
        ];

        $query = $this->newQuery();
        $result = $query->where($where)
            ->field($field)
            ->order($order)
            ->select();
        //echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * @param $likeKeys
     * @param $data
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList($likeKeys, $data, $field = "*")
    {
        $res = $this->newQuery();
        if (!empty($likeKeys)) {
            $res = $res->withSearch($likeKeys, $data);
        }

        $result = $res->field($field)->select();
//        echo $res->getLastSql();exit;
        return $result;
    }

    /**
     * name查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchRepairDescAttr($query, $value)
    {
        $query->where('repair_desc', 'like', '%' . $value . '%');
    }

    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereBetweenTime('create_time', $value[0], $value[1]);
    }

    public function searchCateIdAttr($query, $value)
    {
        $query->where('repair_cate_id', '=', $value);
    }

    public function searchIdAttr($query, $value)
    {
        $query->whereIn('id', $value);
    }

    /**
     * @param $likeKeys
     * @param $data
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getPaginateList($likeKeys, $data, $field = "*", $num = 10)
    {
        $res = $this->newQuery();
        if (!empty($likeKeys)) {
            $res = $res->withSearch($likeKeys, $data);
        }
        $result = $res->field($field)->paginate($num);
        //echo $this->getLastSql();exit;
        return $result;
    }
}
