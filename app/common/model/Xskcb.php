<?php


namespace app\common\model;

use think\Model;

//学生课程表
class Xskcb extends Model
{
    protected $table = 'gyjj_schedule';

    /**
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
        $field = 'XN,XQ,BJMC,KCMC,XQJ,XM';
        if (!$number) {
            return false;
        }
        $where = [
            'xq' => $xq,
            'xn' => $xn,
            'jszgh' => $number
        ];
        $res = $this->where($where)->field($field)->order('xqj asc')->select();
//        $res = $this->where($where)
//            ->field($field)
//            ->group('xqj')
//            ->order('xqj asc')
//            ->select();
        return $res;
    }

    /**
     * @param $zgh
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroup($zgh)
    {
        $res = (new ScheduleGroup())->getGroup($zgh);
        return $res;
    }
}
