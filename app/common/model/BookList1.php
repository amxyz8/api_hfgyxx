<?php


namespace app\common\model;

use think\Model;

//图书列表
class BookList1 extends Model
{
    protected $connection = 'book';
    protected $table = 'v_marc';

    /**
     * @param $data
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPaginateList($data)
    {
        $where = [];
        $field = 'M_TITLE, M_AUTHOR, M_PUB_YEAR, M_PUBLISHER';
        if (isset($data['title']) && !empty($data['title'])) {
            $where[] = ['m_title', 'like', "%{$data['title']}%"];
        }
        $res = $this->where($where)->field($field)->paginate();
        //echo $this->getLastSql();exit();
        $res = $res->toArray();
        return $res;
    }
}
