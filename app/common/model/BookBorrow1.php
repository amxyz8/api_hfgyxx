<?php

namespace app\common\model;

use think\Model;

//我的 未还借阅记录
class BookBorrow1 extends Model
{
    protected $connection = 'book';
    protected $table = 'vlend_all';

    /**
     * @param $id
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPaginateListById($id)
    {
        $where = [
            'cert_id_f' => $id,
            'isret' => 0
        ];
        $field = 'M_TITLE, LEND_DATE, NORM_RET_DATE';
        $res = $this->where($where)->field($field)->paginate();
        $res = $res->toArray();
        return $res;
    }
}
