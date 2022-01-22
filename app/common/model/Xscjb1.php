<?php


namespace app\common\model;

use think\Model;

//学生成绩表
class Xscjb1 extends Model
{
    protected $connection = 'schedule';
    protected $table = 'zfxfzb.cjbview';

    /**
     * 根据学号获取学生课表
     * @param $number
     * @param $xn
     * @param $xq
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList($number, $xn = '', $xq = 1)
    {
        $field = 'XN,XQ,XM,KCMC,CJ,KCXZ';
        if (!$number) {
            return false;
        }
        $where = [
            'xq' => $xq,
            'xn' => $xn,
            'xh' => $number
        ];
        $res = $this->where($where)->field($field)->select();
//        $res = $this->where($where)
//            ->field($field)
//            ->group('xqj')
//            ->order('xqj asc')
//            ->select();
        return $res;
    }

    /**
     * @param $xh
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroup($xh)
    {
        if (!$xh) {
            return false;
        }
        $res = $this->where(['xh' => $xh])->field('xn')->group('xn')->order('xn desc')->select();
        return $res;
    }
}
