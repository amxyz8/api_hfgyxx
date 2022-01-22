<?php
namespace app\admin\controller;

use app\admin\validate\Repair as RepairValidate;
use app\common\lib\Excel as ExcelLib;
use app\common\lib\Show;
use app\common\services\Repair as RepairService;
use think\response\Json;

class Repair extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $cateId = input('param.repair_cate_id', 0, 'intval');
        $time = input('param.time', '', 'trim');
        if ($cateId) {
            $data['repair_cate_id'] = $cateId;
        }
        if (!empty($time)) {
            $data['create_time'] = explode(" - ", $time);
            $data['create_time'][0] = $data['create_time'][0] . " 00:00:00";
            $data['create_time'][1] = $data['create_time'][1] . " 23:59:59";
        }
        $list = (new RepairService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    /**
     * @return Json
     */
    public function export()
    {
        $data = [];
        $input = input('param.');

        $validate = new RepairValidate();
        if (!$validate->scene('export')->check($input)) {
            return Show::error($validate->getError());
        }

        $cateId = $input['repair_cate_id'];

        if (!empty($cateId)) {
            $data['repair_cate_id'] = $cateId;
        }

        // 查询要导出的数据
        $result = (new RepairService())->getList($data);

        if (!$result) {
            return Show::error('没有数据可导出');
        }

        $data = [];

        foreach ($result as $k => $v) {
            $data[$k]['catename']=$v['catename'];
            $data[$k]['username']=$v['username'];
            $data[$k]['approvername']=$v['approvername'];
            $data[$k]['reparename']=$v['reparename'];
            $data[$k]['comment']=$v['comment'];
            $data[$k]['status']=$v['status'];
            $data[$k]['create_time']=$v['create_time'];
        }
//        $filename = "报修数据文档".date('YmdHis');
        $filename = "报修数据文档";
        $header = [
            ['column' => 'catename', 'name' => '分类名称', 'width' => 15],
            ['column' => 'username', 'name' => '报修人', 'width' => 15],
            ['column' => 'approvername', 'name' => '审批人', 'width' => 15],
            ['column' => 'reparename', 'name' => '维修人', 'width' => 15],
            ['column' => 'status', 'name' => '状态', 'width' => 15],
            ['column' => 'comment', 'name' => '评价', 'width' => 30],
            ['column' => 'create_time', 'name' => '提交时间', 'width' => 30],
        ];
        $download_url=(new ExcelLib())->exportSheelExcel($data, $header, $filename);//获取下载链接

        if ($download_url) {
            return Show::success(['url' => $download_url]);
        }

        return Show::error();
    }

    /**
     * @return Json
     */
    public function cexport()
    {
        $data = [];
        $input = input('param.');
    
        $validate = new RepairValidate();
        if (!$validate->scene('export')->check($input)) {
            return Show::error($validate->getError());
        }
    
        $cateId = $input['repair_cate_id'];
        $time = $input['time']??'';
        
        if (!empty($cateId)) {
            $data['repair_cate_id'] = $cateId;
        }
        if (!empty($time)) {
            $data['create_time'] = explode(" - ", $time);
        }

        // 查询要导出的数据
        $result = (new RepairService())->getExportList($data);

        if (!$result) {
            return Show::error('没有数据可导出');
        }
        
        $cate = (new \app\common\services\RepairCate())->getNormalById($cateId);

        $data = [];

        foreach ($result as $k => $v) {
            $data[$k]['cate_name']=$cate['name']??'';
            $data[$k]['count']=$v['count']??0;
        }
//        $filename = "报修数据文档".date('YmdHis');
        $filename = "报修数据文档";
        $header = [
            ['column' => 'cate_name', 'name' => '分类名称', 'width' => 15],
            ['column' => 'count', 'name' => '报修总数', 'width' => 15],
        ];
        $download_url=(new ExcelLib())->exportSheelExcel($data, $header, $filename);//获取下载链接

        if ($download_url) {
            return Show::success(['url' => $download_url]);
        }

        return Show::error();
    }
}
