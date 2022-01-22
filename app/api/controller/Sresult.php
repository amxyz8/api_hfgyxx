<?php

namespace app\api\controller;

use app\api\validate\Sresult as SresultValidate;
use app\common\lib\Show;
use app\common\services\Selection;
use app\common\services\SelectionResult;
use think\response\Json;

class Sresult extends AuthBase
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

        $validate = new SresultValidate();
        if (!$validate->scene('save')->check($data)) {
            return Show::error($validate->getError());
        }

        $info = (new Selection())->getNormalById($data['selection_id']);
        if (!$info) {
            return Show::error('数据不存在');
        }

        $date = date('Y-m-d');

        if ($date > $info['end_time'] || $date < $info['start_time']) {
            return Show::error('还未到参与时间哦');
        }

        $insertData = [];
        foreach ($data['option_id'] as $key => $datum) {
            $temp = [
                'user_id' => $this->userId,
                'selection_id' => $data['selection_id'],
                'option_id' => $datum,
            ];
            array_push($insertData, $temp);
        }

        try {
            $result = (new SelectionResult())->insertAll($insertData);
            (new Selection())->updateAttendCount($data['selection_id']);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        
        return Show::success($result);
    }
}
