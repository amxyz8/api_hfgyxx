<?php
namespace app\api\controller;

use app\common\lib\Show;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\DescribeAllClassRequest;
use TencentCloud\Vod\V20180717\Models\SearchMediaRequest;
use TencentCloud\Vod\V20180717\VodClient;

class Tencent extends ApiBase
{
    protected $config;
    protected $secrctId = '';
    protected $secrctKey = '';
    protected $url = '';
    
    public function __construct()
    {
        $this->config = config('video');
        $this->secrctId = $this->config['secret_id'];
        $this->secrctKey = $this->config['secret_key'];
        $this->url = $this->config['url'];
    }
    
    public function index()
    {
        $res = (new \app\common\services\Video())->videoSync();
        return Show::success($res);
    }


    public function clist()
    {
        $res = (new \app\common\services\VideoCategory())->cateSync();
        return Show::success($res);
    }
}
