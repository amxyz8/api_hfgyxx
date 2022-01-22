<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\concern\SoftDelete;

//架构人员表
class DepartmentUser extends BaseModel
{
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
     * @param $number
     * @return array|bool|\think\Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getByNumber($number)
    {
        $res = $this->where(['number' => $number])->find();
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

        $result = $this->where($where)
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
        $order = [
            'id' => 'desc'
        ];
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        $result = $res->order($order)->select();
//        echo $res->getLastSql();exit;
        return $result;
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
        $result = $res->order($order)->paginate($num);
//        echo $this->getLastSql();exit;
        return $result;
    }
}
