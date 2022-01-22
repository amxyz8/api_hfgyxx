<?php

namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\QuestionProblem as ProblemServices;
use app\api\validate\Problem as ProblemValidate;
use app\common\services\QuestionResult;
use think\response\Json;

class Problem extends AuthBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = input('param.');
        $validate = new ProblemValidate();
        if (!$validate->scene('index')->check($data)) {
            return Show::error($validate->getError());
        }

        $id = $data['question_id'];
        $isSubmit = 0;
        $date = date("Y-m-d");
        $userId = $this->userId;
        try {
            $info = (new \app\common\services\Question())->getNormalById($id);
            if ($info['end_time'] < $date) {
                return Show::error('此项活动已结束');
            }
            $list = (new ProblemServices())->getNormalListWithOption($id);
            $isSubmit = (new QuestionResult())->isSubmit($id, $userId);
        } catch (\Exception $e) {
            $list = [];
        }

        $res['list'] = $list;
        $res['is_submit'] = $isSubmit;

        return Show::success($res);
    }
}
