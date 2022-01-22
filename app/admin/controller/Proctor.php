<?php
namespace app\admin\controller;

use app\admin\validate\Proctor as ProctorValidate;
use app\common\lib\Excel as ExcelLib;
use app\common\lib\Show;
use app\common\services\Proctor as ProctorService;
use think\facade\Log;
use think\response\Json;

//监考
class Proctor extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $number = input("param.number", "", "trim");
        $name = input("param.name", "", "trim");

        if (!empty($number)) {
            $data['number'] = $number;
        }
        if (!empty($name)) {
            $data['name'] = $name;
        }
        $list = (new ProctorService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    /**
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new ProctorValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new ProctorService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/proctor/save 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }

    /**
     * 详情
     * @return Json
     */
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        try {
            $result = (new ProctorService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/question/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage());
        }

        return Show::success($result);
    }

    /**
     * 更新数据
     * @return Json
     */
    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }

        $id = input('param.id', 0, 'intval');
        $data = input('post.');

        try {
            $res = (new ProctorService())->update($id, $data);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
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
            $res = (new ProctorService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }

    /**
     * @return Json
     */
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
            $temp = [
                'title' => $datum['A'],
                'class' => $datum['B'],
                'place' => $datum['C'],
                'subject' => $datum['D'],
                'date' => $datum['E'],
                'time_period' => $datum['F'],
                'name1' => trim($datum['G']),
                'name2' => trim($datum['H']),
                'desc' => $datum['I'],
            ];
            $userNames = [$datum['G'], $datum['H']];
            $users = (new \app\common\services\User())->getUserByNames($userNames);
            if ($users) {
                foreach ($users as $key => $user) {
                    $str = 'number'.($key+1);
                    $temp[$str] = $user['number'];
                }
            }

            //格式化文档日期
            $arr = date_parse_from_format('m月d日', $datum['E']);
            $formatDate = mktime(0, 0, 0, $arr['month'], $arr['day'], date('Y', time()));
            $temp['format_date'] = date('Y-m-d', $formatDate);
            array_push($insertData, $temp);
        }

        $res = (new ProctorService())->addAll($insertData);
        if (!$res) {
            return Show::error('插入失败');
        }
        return Show::success();
    }
}
