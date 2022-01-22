<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;
use think\model\concern\SoftDelete;

class Repair extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $dateFormat = 'Y-m-d';

    protected $type = [
        'start_time'  =>  'timestamp',
        'end_time'  =>  'timestamp'
    ];

    public static $statusMap = ['拒绝', '已提交', '已审核', '已维修', '已完成'];

    protected $hidden = [
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

        $result = $res->field($field)->select();
//        echo $res->getLastSql();exit;
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
    public function getExportList($likeKeys, $data, $field = "*")
    {
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        
        //		$result = $res->field($field)->select();
        $result = $this->where('repair_cate_id', '=', $data['repair_cate_id'])
            ->field(["repair_cate_id", "count(*) as count"])
            ->group("repair_cate_id")
            ->select();
//        echo $res->getLastSql();exit;
        return $result;
    }

    public function searchProgressBarAttr($query, $value)
    {
        $query->whereIn('progress_bar', $value);
    }

    public function searchUserIdAttr($query, $value)
    {
        $query->where('user_id', '=', $value);
    }

    public function searchRepareIdAttr($query, $value)
    {
        $query->where('repare_id', '=', $value);
    }

    public function searchApproverIdAttr($query, $value)
    {
        $query->where('approver_id', '=', $value);
    }

    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereBetweenTime('create_time', $value[0], $value[1]);
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
        $order = [
            'id' => 'desc'
        ];
        if ($likeKeys) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        $result = $res->field($field)->order($order)->paginate($num);
//        echo $this->getLastSql();exit;
        return $result;
    }
}
