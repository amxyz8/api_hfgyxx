<?php


namespace app\api\controller;

use app\common\lib\Show;
use app\common\model\Xsksls as XskslsModel;
use think\facade\View;

class Test extends ApiBase
{
    public function index()
    {
        return View::fetch();
    }
    
    public function check()
    {
        if (!$this->request->isPost()) {
            return Show::error('请求方式错误');
        }
        $code = input('username', '', 'trim');
        if (!$code) {
            return Show::error('请输入考生号');
        }

        $info = (new XskslsModel())->get($code);

        if (!$info) {
            return Show::error('未查到数据, 请重新输入正确的考生号');
        }
    
        return Show::success($info);
    }
    
    public function success()
    {
        $id = input('id', 0, 'intval');
        $info = (new XskslsModel())->getById($id);
        return View::fetch('success', [
            'xm' => $info['xm']??"",
            'ksh' => $info['ksh']??"",
            'fjh' => $info['fjh']??"",
            'kstime' => $info['kstime']??"",
        ]);
    }
}
