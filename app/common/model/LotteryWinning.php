<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\concern\SoftDelete;

class LotteryWinning extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

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
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }

        $result = $res->field($field)->order('id desc')->select();
//        echo $res->getLastSql();exit;
        return $result;
    }

    /**
     * lottery_id查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchLotteryIdAttr($query, $value)
    {
        $query->where('lottery_id', '=', $value);
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
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        $result = $res->field($field)->paginate($num);
        //echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * @param $id
     * @return int
     */
    public function getCountByLotteryId($id)
    {
        $where = [
            'lottery_id' => $id,
        ];

        $count = $this->where($where)->count();
//        echo $this->getLastSql();exit;
        return $count;
    }
}
