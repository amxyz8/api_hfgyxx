<?php

namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\model\BookBorrow1 as BookBorrowModel;
use app\common\model\BookList1 as BookListModel;
use think\response\Json;

class Books extends AuthBase
{
    /**
     * 首页列表
     * @return Json
     */
    public function index()
    {
        $data = input('param.');
        try {
            $list = (new BookListModel())->getPaginateList($data);
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }

        return Show::success($list);
    }

    /**
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function mindex()
    {
        $list = [];
        $user = (new \app\common\services\User())->getNormalUserById($this->userId);
        if (!$user) {
            return Show::success($list);
        }

        $list = (new BookBorrowModel())->getPaginateListById($user['number']);

        return Show::success($list);
    }
}
