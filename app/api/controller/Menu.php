<?php


namespace app\api\controller;

use app\common\services\Menu as MenuService;

use app\common\lib\Arr;
use app\common\lib\Show;
use think\response\Json;

class Menu extends ApiBase
{
    /**
     * @return Json
     */
    public function index()
    {
        $data = [
            'is_show' => 1
        ];
        try {
            $list = (new MenuService())->getNormalList($data);
        } catch (\Exception $e) {
            $list = Arr::getPaginateDefaultData(10);
        }

        return Show::success($list);
    }
}
