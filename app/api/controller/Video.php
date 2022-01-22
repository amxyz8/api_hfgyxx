<?php

namespace app\api\controller;

use app\api\validate\Video as VideoValidate;
use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\Video as VideoServices;
use app\common\services\VideoCategory;
use think\response\Json;

//腾讯云视频
class Video extends AuthBase
{
    /**
     * 视频列表
     * @return Json
     */
    public function index()
    {
        $data = [];
        $classId = input('param.class_id', 0, 'intval');
        $title = input('param.title', '', 'trim');
        if ($classId) {
            $data['class_id'] = $classId;
        }

        if ($title) {
            $data['title'] = $title;
        }
        try {
            $list = (new VideoServices())->getPaginateList($data, 10);
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }

        return Show::success($list);
    }

    /**
     * 视频分类列表
     * @return Json
     */
    public function clist()
    {
        try {
            $field = 'id, class_name, class_id';
            $list = (new VideoCategory())->getList($field);
        } catch (\Exception $e) {
            $list = [];
        }

        return Show::success($list);
    }

    /**
     * 详情
     * @return Json
     */
    public function read()
    {
        $data = input('param.');

        $validate = new VideoValidate();
        if (!$validate->scene('read')->check($data)) {
            return Show::error($validate->getError());
        }

        $id = $data['id'];
        try {
            $result = (new VideoServices())->getNormalById($id);
        } catch (\Exception $e) {
            $result = [];
        }

        return Show::success($result);
    }
}
