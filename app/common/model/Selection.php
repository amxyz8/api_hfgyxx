<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\concern\SoftDelete;

class Selection extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $dateFormat = 'Y-m-d';

    protected $type = [
        'start_time'  =>  'timestamp',
        'end_time'  =>  'timestamp'
    ];

    protected $hidden = [
        'create_time',
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
     * 根据title查询数据
     * @param $title
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNewsByTitle($title)
    {
        if (empty($title)) {
            return false;
        }

        $where = [
            'title' => $title,
            'status' => config('status.mysql.table_normal'),
        ];

        return $this->where($where)->find();
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

        $query = $this->newQuery();
        $result = $query->where($where)
            ->field($field)
//            ->order($order)
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
     * target查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchTargetAttr($query, $value)
    {
        $query->where('target', 'in', [0, $value]);
    }
    
    /**
     * status查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        $query->where('status', '=', $value);
    }

    /**
     * status查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchEndTimeAttr($query, $value)
    {
        $query->whereTime('end_time', '>', $value);
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
            'start_time' => 'desc'
        ];
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        $result = $res->field($field)->order($order)->paginate($num);
//        echo $this->getLastSql();exit;
        return $result;
    }

    public function incCount($id, $num)
    {
        return $this->where("id", "=", $id)
            ->inc("attend_count", $num)
            ->update();
    }
}
