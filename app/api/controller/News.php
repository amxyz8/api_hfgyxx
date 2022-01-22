<?php


namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\News as NewsService;

class News extends ApiBase
{
    public function index()
    {
        $cateId = input('param.cate_id', '0', 'intval');
        try {
            if ($cateId) {
                $list = (new NewsService())->getPaginateList(['cate_id' => $cateId], 10);
            } else {
                $list = (new NewsService())->getNormalAllNews();
            }
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }
        return Show::success($list);
    }
    
    public function read()
    {
        $id = input('param.id', 0, 'intval');
        try {
            $result = (new NewsService())->formatNews($id);
            $res = (new NewsService())->updateReadCount($id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage(), $e->getCode());
        }

        return Show::success($result);
    }
}
