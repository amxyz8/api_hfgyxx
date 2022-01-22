<?php


namespace app\common\model;

use think\Model;

//老师课程表
class ScheduleGroup extends Model
{
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
        $field = 'xn,xq,bjmc,kcmc,xqj,xm';
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
        if (!$zgh) {
            return false;
        }
        $res = $this->field('XN')->order('XN desc')->group('XN')->select();
//        echo $this->getLastSql();exit();
//        $res = $this->where(['jszgh' => $zgh])->field('xn,xq')->group('xn,xq')->order('xn desc')->select();
        return $res;
    }
}
