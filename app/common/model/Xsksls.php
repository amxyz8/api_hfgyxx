<?php


namespace app\common\model;

use think\Model;

//学生考试临时表
class Xsksls extends Model
{

    /**
     * @param $ksh
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get($ksh)
    {
        if (!$ksh) {
            return false;
        }
        $res = $this->where(['ksh' => $ksh])->find();
        return $res;
    }
    
    public function getById($id)
    {
        if (!$id) {
            return false;
        }
        $res = $this->find($id);
        return $res;
    }
}
