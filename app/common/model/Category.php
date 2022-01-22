<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;
use think\model\concern\SoftDelete;

class Category extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $hidden = [
        'create_time',
        'update_time',
        'delete_time',
        'path'
    ];

    /**
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalCategorys($field = "*")
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];
        
        $order = [
            "sequence" => "desc",
            "id" => "asc"
        ];
        $result = $this->whereNotIn('id', [7,13,14])
            ->whereNotIn('pid', [7,13,14])
            ->where($where)
            ->field($field)
            ->order($order)
            ->select();
        
        return $result;
    }
    
    /**
     * 根据name查询数据
     * @param $name
     * @return array|bool|Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getCateByName($name)
    {
        if (empty($name)) {
            return false;
        }
        
        $where = [
            'name' => $name
        ];
        
        return $this->where($where)->find();
    }
    
    /**
     * @param $id
     * @return array|bool|Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getCateById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        return $this->find($id);
    }
    
    /**
     * 根据ids获取分类信息
     * @param array $ids
     * @return bool|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCateByIds($ids = [])
    {
        if (empty($ids)) {
            return false;
        }
        
        return $this->whereIn('id', $ids)->select();
    }
    
    /**
     * name查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchNameAttr($query, $value)
    {
        $query->where('name', 'like', '%' . $value . '%');
    }
    
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereBetweenTime('create_time', $value[0], $value[1]);
    }
    
    /**
     * 获取列表数据
     * @param $where
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getLists1($likeKeys, $data, $field = '*', $num = 10)
    {
        $order = [
            "sequence" => "desc",
            "id" => "desc"
        ];
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        $result = $res->whereIn('status', [0, 1])
            ->field($field)
            ->order($order)
            ->paginate($num);
//        echo $this->getLastSql();exit();
        return $result;
    }
    
    /**
     * 获取列表数据
     * @param $where
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getPaginateList($where, $field = '*', $num = 10)
    {
        $order = [
            "sequence" => "desc",
            "id" => "desc"
        ];
        $result = $this->where("status", "=", config("status.mysql.table_normal"))
            ->where($where)
            ->field($field)
            ->order($order)
            ->paginate($num);
//        echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * getChildCountInPids
     * @param $condition
     * @return mixed
     */
    public function getChildCountInPids($condition)
    {
        $where[] = ["pid", "in", $condition['pid']];
        $res = $this->where($where)
            ->field(["pid", "count(*) as count"])
            ->group("pid")
            ->select();
        //echo $this->getLastSql();exit;
        return $res;
    }

    /**
     * getChildListInPids
     * @param $condition
     * @return mixed
     */
    public function getChildListInPids($condition)
    {
        $where[] = ["pid", "in", $condition['pid']];
        $res = $this->where($where)
            ->field(["id", "pid", "name"])
//            ->group("pid")
            ->select();
//        echo $this->getLastSql();exit;
        return $res;
    }
    
    /**
     * 根据主键ID更新数据表中的数据
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateById($id, $data)
    {
        $id = intval($id);
        if (empty($id) || empty($data) || !is_array($data)) {
            return false;
        }
        
        $where = [
            "id" => $id,
        ];
        
        return $this->where($where)->save($data);
    }
}
