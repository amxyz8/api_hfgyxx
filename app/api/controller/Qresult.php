<?php

namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\Question;
use app\common\services\QuestionResult;
use app\common\services\QuestionSuggest;
use think\response\Json;

class Qresult extends AuthBase
{
    /**
     * 新增
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return Show::error('非法请求');
        }
        $data = input('param.');

        $questionId = $data['question_id']??0;

        $info = (new Question())->getNormalById($questionId);
        if (!$info) {
            return Show::error('数据不存在');
        }

        $date = date('Y-m-d');

        if ($date > $info['end_time'] || $date < $info['start_time']) {
            return Show::error('还未到参与时间哦');
        }

//        $validate = new QresultValidate();
//        if (!$validate->scene('save')->check($data)) {
//            return Show::error($validate->getError());
//        }
        $qData = [];
        foreach ($data['option_res'] as $key => $datum) {
            foreach ($datum as $k => $v) {
                $temp = [
                    'question_id' => $questionId,
                    'problem_id' => $key,
                    'option_id' => $v,
                    'user_id' => $this->userId
                ];
                array_push($qData, $temp);
            }
        }

        $suggestData = [
            'question_id' => $questionId,
            'content' => $data['content'],
        ];

        try {
            $res = (new QuestionResult())->insertAll($qData);
            (new QuestionSuggest())->insertData($suggestData);
            (new Question())->updateAttendCount($questionId);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success();
    }
}
