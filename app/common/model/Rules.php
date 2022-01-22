<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\concern\SoftDelete;

class Rules extends BaseModel
{

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
        $res = $this->field('id,v1,v2')->find($id);
//        echo $this->getLastSql();exit();
        return $res;
    }

    /**
     * @param $data
     * @param $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getListByType($data, $field)
    {
        $where = [
            "ptype" => $data['type'],
        ];
        
        if (isset($data['uid'])) {
            $where['v0'] = $data['uid'];
        }

        $result = $this->where($where)->field($field)->select();
//        echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getGlist()
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];

        $result = $this->where($where)
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
     * title查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchTitleAttr($query, $value)
    {
        $query->where('title', 'like', '%' . $value . '%');
    }

    /**
     * @param $likeKeys
     * @param $data
     * @param string $field
     * @param int $num
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getPaginateList($likeKeys, $data, $field = "*", $num = 10)
    {
        $order = [
            'id' => 'desc'
        ];
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        $result = $res->field($field)->order($order)->paginate($num);
        //echo $this->getLastSql();exit;
        return $result;
    }
}
