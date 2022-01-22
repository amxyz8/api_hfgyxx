<?php
namespace app\admin\controller;

use app\admin\validate\QuestionOption as QuestionOptionValidate;
use app\common\lib\Show;
use app\common\services\QuestionOption as QuestionOptionService;
use think\facade\Log;
use think\response\Json;

class Option extends AdminAuthBase
{
    /**
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $data = input('param.');
        $validate = new QuestionOptionValidate();
        if (!$validate->scene('index')->check($data)) {
            return Show::error($validate->getError());
        }
        $qid = $data['question_id'];
        $pid = $data['problem_id'];
        $list = (new QuestionOptionService())->getPaginateList($qid, $pid, 10);
        
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

        $validate = new QuestionOptionValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $result = (new QuestionOptionService())->insertData($data);
        } catch (\Exception $e) {
            Log::error('admin/option/save 错误:' . $e->getMessage());
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
            $result = (new QuestionOptionService())->getNormalById($id);
        } catch (\Exception $e) {
            Log::error('admin/option/read 错误:' . $e->getMessage());
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
            $res = (new QuestionOptionService())->update($id, $data);
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
            $res = (new QuestionOptionService())->del($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }

        return Show::success();
    }
}
