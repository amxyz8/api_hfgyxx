<?php
namespace app\admin\controller;

use app\common\lib\Excel as ExcelLib;
use app\common\lib\Key;
use app\common\lib\Show;
use app\common\services\Salary as SalaryService;
use think\facade\Cache;
use think\response\Json;

class Salary extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $passWord = input('param.password', '', 'trim');

        $value = Cache::get(Key::SalaryPassWordKey());
        if (!$value) {
            $value = Cache::set(Key::SalaryPassWordKey(), '000000');
        }

        if ($passWord != $value) {
            return Show::error('权限不足');
        }
        
        $data = [];
        $name = input('param.username', '', 'trim');
        $number = input('param.number', '', 'trim');
        $time = input('param.month', '', 'trim');
        if (!empty($name)) {
            $data['username'] = $name;
        }
        if (!empty($number)) {
            $data['number'] = $number;
        }
        if (!empty($time)) {
            $time = explode(" - ", $time);
            $data['start_month'] = $time[0];
            $data['end_month'] = $time[1];
        }
        $list = (new SalaryService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    public function import()
    {
        $excel = new ExcelLib();
        $data = $excel->importExcel();

        if (empty($data)) {
            return Show::error('导入内容为空');
        }

        $insertData = [];
        foreach ($data as $datum) {
            if (empty($datum['A'])) {
                continue;
            }
            $date = explode('.', $datum['A']);
            $month = $date[0].'-'.$date[1];
            $month = date('Y-m', strtotime($month));
            $temp = [
                'month' => $month,
                'number' => $datum['B'],
                'username' => $datum['C'],
                'tfgzhj' => $datum['D'],
                'gwgz' => $datum['E'],
                'xjgz' => $datum['F'],
                'jcxjxgz' => $datum['G'],
                'jhtg' => $datum['H'],
                'tfbf' => $datum['I'],
                'dfgzhj' => $datum['J'],
                'jhljt' => $datum['K'],
                'ft' => $datum['L'],
                'dszvf' => $datum['M'],
                'hmbt' => $datum['N'],
                'jtbt' => $datum['O'],
                'dfbf' => $datum['P'],
                'dkgzhj' => $datum['Q'],
                'gjj' => $datum['R'],
                'yb' => $datum['S'],
                'sybx' => $datum['T'],
                'ylbx' => $datum['U'],
                'zynj' => $datum['V'],
                'ghhf' => $datum['W'],
                'dwdkgs' => $datum['X'],
                'fz' => $datum['Y'],
                'qtdk' => $datum['Z'],
                'yfgzhj' => $datum['AA'],
                'sfgzhj' => $datum['AB'],
                'jbgz' => $datum['AC'],
                'xljt' => $datum['AD'],
                'qtbf' => $datum['AE'],
                'yfgz' => $datum['AF'],
                'gjj1' => $datum['AG'],
                'ylbx1' => $datum['AH'],
                'sybx1' => $datum['AI'],
                'ylbx11' => $datum['AJ'],
                'ghhf1' => $datum['AK'],
                'dwdkgs1' => $datum['AL'],
                'qtdk1' => $datum['AM'],
                'dkgzxj' => $datum['AN'],
                'sfgz' => $datum['AO'],
                'jlxjx' => $datum['AP'],
                'ksjt' => $datum['AQ'],
                'zbf' => $datum['AR'],
                'kwf' => $datum['AS'],
                'jndsjb' => $datum['AT'],
                'zjgkjb' => $datum['AU'],
                'qtjb' => $datum['AV'],
                'ylf' => $datum['AW'],
                'hsbz' => $datum['AX'],
                'wwf' => $datum['AY'],
                'other1' => $datum['AZ'],
                'other2' => $datum['BA'],
                'jbfyzj' => $datum['BB'],
            ];
            array_push($insertData, $temp);
        }

        $res = (new SalaryService())->addAll($insertData);
        if (!$res) {
            return Show::error('插入失败');
        }
        return Show::success();
    }

    /**
     * 删除数据
     * @return Json
     */
    public function delete()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input("param.id");

        try {
            $res = (new SalaryService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * 设置权限密码
     * @return Json
     */
    public function spwd()
    {
        $passWord1 = input('param.password1', '', 'trim');
        $passWord2 = input('param.password2', '', 'trim');
        $value = Cache::get(Key::SalaryPassWordKey());
        if ($passWord1 != $value) {
            return Show::error('密码不正确');
        }
        $value = Cache::set(Key::SalaryPassWordKey(), $passWord2);
        return Show::success();
    }

    /**
     * 验证密码
     * @return Json
     */
    public function check()
    {
        $passWord = input('param.password', '', 'trim');
        $value = Cache::get(Key::SalaryPassWordKey());
        if ($passWord != $value) {
            return Show::error('密码不正确');
        }
        return Show::success();
    }
}
