<?php
namespace app\admin\controller;

use app\admin\validate\Question as QuestionValidate;
use app\common\lib\Excel as ExcelLib;
use app\common\lib\Show;
use app\common\services\Question as QuestionService;
use app\common\services\QuestionResult as QresultService;
use think\facade\Log;
use think\response\Json;

//问卷调查
class Question extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = [];
        $title = input("param.title", "", "trim");
        $status = input("param.status", "", "trim");

        if (!empty($title)) {
            $data['title'] = $title;
        }
        if (!empty($status)) {
            $data['status'] = $status;
        }
        $list = (new QuestionService())->getPaginateList($data, 10);
        
        return Show::success($list);
    }

    /**
     * 新增
     * @return Json
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('post.');

        $validate = new QuestionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $data['user_id'] = $this->userId;
        $data['start_time'] = $data['start_time'] . '00:00:00';
        $data['end_time'] = $data['end_time'] . '23:59:59';

        try {
            $result = (new QuestionService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/question/save 错误:' . $e->getMessage());
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
            $result = (new QuestionService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/question/read 错误:' . $e->getMessage());
            return Show::error($e->getMessage(), $e->getCode());
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
//        $data = $this->request->only(['is_hot', 'is_top', 'title', 'content'], 'post');

        try {
            $res = (new QuestionService())->update($id, $data);
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
            $res = (new QuestionService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
    
    /**
     * 图表导出
     * @return Json
     */
    public function estats()
    {
        $data = input('post.');
        
        $validate = new QuestionValidate();
        if (!$validate->scene('export')->check($data)) {
            return Show::error($validate->getError());
        }
        
        $qid = $data['question_id'];
        
        // 查询要导出的数据
        $result = (new QresultService())->getGroupOptionCount($qid);
        
        if (!$result) {
            return Show::error('没有数据可导出');
        }
        
        $excel = new ExcelLib();
        $download_url = $excel->barSheet($result);
        
        if ($download_url) {
            return Show::success(['url' => $download_url]);
        }
        
        return Show::error();
    }
}
