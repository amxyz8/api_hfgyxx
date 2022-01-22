<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\concern\SoftDelete;

class QuestionProblem extends BaseModel
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
    
    public function QuestionOption()
    {
        return $this->hasMany(QuestionOption::class, 'problem_id');
    }

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
     * @param integer $id
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalList($id, $field = "*")
    {
        $order = [
            'sequence' => 'asc',
            'id' => 'asc',
        ];
        $where = [
            "question_id" => $id,
            "status" => config("status.mysql.table_normal"),
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
     * @param integer $id
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalListWithOption($id, $field = "*")
    {
        $order = [
            'sequence' => 'asc',
            'id' => 'asc',
        ];
        $where = [
            "question_id" => $id,
            "status" => config("status.mysql.table_normal"),
        ];
        
        $result = $this->where($where)
            ->with(['questionOption' => function ($query) {
                $query->field('id, problem_id, value');
            }])
            ->field($field)
            ->order($order)
            ->select();
//        echo $this->getLastSql();
//        exit;
        return $result;
    }

    /**
     * @param integer $id
     * @param string $field
     * @param string $num
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getPaginateListWithOption($id, $field = "*", $num)
    {
        $order = [
            'sequence' => 'asc',
            'id' => 'asc',
        ];
        $where = [
            "question_id" => $id,
            "status" => config("status.mysql.table_normal"),
        ];

        $result = $this->where($where)
            ->with(['questionOption' => function ($query) {
                $query->field('id, problem_id, value');
            }])
            ->field($field)
            ->order($order)
            ->paginate($num);
//        echo $this->getLastSql();
//        exit;
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
     * @param $id
     * @param string $field
     * @param int $num
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getPaginateList($id, $field = "*", $num = 10)
    {
        $order = [
            "sequence" => "asc",
            "id" => "asc",
        ];
        $result = $this->where(['question_id' => $id])->field($field)->order($order)->paginate($num);
        //echo $this->getLastSql();exit;
        return $result;
    }
}
