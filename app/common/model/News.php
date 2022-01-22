<?php


namespace app\common\model;

use think\model\concern\SoftDelete;
use think\model\Relation;

class News extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $dateFormat = 'Y-m-d';
    public $allowField = [
        'title',
        'small_title',
        'user_id',
        'cate_id',
        'status',
        'img_urls',
        'is_hot',
    ];

    protected $type = [
        'pub_date'  =>  'timestamp',
    ];

    protected $hidden = [
        'create_time',
        'update_time',
        'delete_time'
    ];

    /**
     * @param $likeKeys
     * @param $data
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException\
     */
    public function getPaginateList($likeKeys, $data, $field = "*", $num = 10)
    {
        $order = [
            "pub_date" => "desc"
        ];
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }

        if (!isset($data['cate_id']) || !$data['cate_id']) {
            $res = $res->whereNotIn('cate_id', [7,13,14,20,21]);
        }

        $result = $res->field($field)->order($order)->paginate($num);
//        echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * @param $likeKeys
     * @param $data
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException\
     */
    public function getVideoPaginateList($likeKeys, $data, $field = "*", $num = 10)
    {
        $order = [
            "pub_date" => "desc"
        ];
        if (!empty($likeKeys)) {
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }

        if (!isset($data['cate_id']) || !$data['cate_id']) {
            $res = $res->whereIn('cate_id', [7,20,21]);
        }

        $result = $res->field($field)->order($order)->paginate($num);
//        echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * cate_id查询条件表达式
     * 调用withSearch方法时触发
     * @param $query
     * @param $value
     */
    public function searchCateIdAttr($query, $value)
    {
        $query->where('cate_id', '=', $value);
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
    
    public function NewsContent()
    {
        return $this->hasOne(NewsContent::class);
    }

    /**
     * @param string $field
     * @param string $num
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalNews($field = "*", $num)
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];
        
        $order = [
            "pub_date" => "desc"
        ];
        $result = $this->whereIn('cate_id', [1,2,3,4,5,6,8,9,10,11])
            ->where($where)
            ->field($field)
            ->order($order)
            ->limit($num)
            ->select();
        
        return $result;
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
     * @param $id
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNewsById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        $res = $this->withJoin(['newsContent' => function (Relation $query) {
            $query->withField(['content']);
        }])->find($id);
        if (!$res) {
            $res = $this->find($id);
        }
        //	    echo $this->getLastSql();exit();
        return $res;
    }

    /**
     * 根据id更新关联模型newsContent数据
     * @param $id
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateContentRelation($id, $data)
    {
        $res = $this->find($id);
        $res->NewsContent->content = $data['content'];
        $res->NewsContent->update_time = time();
        return $res->together(['NewsContent'])->save($data);
    }
    
    /**
     * @param $cateId
     * @param string $num
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLimitByCateId($cateId = 0, $num = 50)
    {
        $where = [
            "cate_id" => $cateId,
        ];
        
        $order = [
            "pub_date" => "desc"
        ];
        $result = $this->where($where)
            ->order($order)
            ->limit($num)
            ->select();
        //		echo $this->getLastSql();exit();

        return $result;
    }

    public function incCount($id, $num)
    {
        return $this->where("id", "=", $id)
            ->inc("read_count", $num)
            ->update();
    }
}
