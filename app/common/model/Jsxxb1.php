<?php


namespace app\common\model;

use think\Model;

//教师信息表
class Jsxxb1 extends Model
{
    protected $connection = 'schedule';
    protected $table = 'zfxfzb.v_jsxxb';

    /**
     * @param $zgh
     * @param $flag
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getByZGH($zgh, $flag)
    {
        if (!$zgh || !$flag) {
            return false;
        }
        $res = $this->where(['ZGH' => $zgh, 'LXDH' => $flag])->find();
        return $res;
    }
}
