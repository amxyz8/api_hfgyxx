<?php


namespace app\api\controller;

use app\common\lib\Show;
use app\common\services\Banner as BannerServices;

class Banner extends ApiBase
{
    public function index()
    {
        $data = [
            "status" => 1,
            "is_show" => 1,
        ];
        try {
            $res = (new BannerServices())->getNormalList($data);
        } catch (\Exception $e) {
            $res = [];
        }
    
        return Show::success($res);
    }
}
