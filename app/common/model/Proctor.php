<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\concern\SoftDelete;

//老师监考表
class Proctor extends BaseModel
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
     * @param array $data
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getDateGroup($data = [])
    {
        $field = 'format_date';
        $order = [
            'date' => 'asc'
        ];

        $result = $this->where('format_date', '>=', $data['date'])
            ->where(function ($query) use ($data) {
                $query->where('number1', $data['number'])->whereOr('number2', $data['number']);
            })
//            ->whereOr('number2', '=', $data['number'])
            ->field($field)
            ->order($order)
            ->group($field)
            ->select();
//        echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * @param array $data
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getListByDateAndNumber($data = [])
    {
        $field = '*';
        $order = [
            'date' => 'asc'
        ];

        $where = [];
        if (!$data['date']) {
            $data['date'] = date('Y-m-d', time());
            $where[] = ['format_date', '>=', $data['date']];
        } else {
            $where[] = ['format_date', '=', $data['date']];
        }

        $result = $this->where($where)
            ->where(function ($query) use ($data) {
                $query->where('number1', $data['number'])->whereOr('number2', $data['number']);
            })
            ->field($field)
            ->order($order)
            ->select();
//        echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * number查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchNumberAttr($query, $value)
    {
        $query->where('number', '=', $value);
    }

    /**
     * number查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchNameAttr($query, $value)
    {
        $query->where('name1', 'like', "%$value%")->whereOr('name2', 'like', "%$value%");
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
//        echo $this->getLastSql();exit;
        return $result;
    }
}
