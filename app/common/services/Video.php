<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\model\Video as VideoModel;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\SearchMediaRequest;
use TencentCloud\Vod\V20180717\VodClient;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

class Video extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new VideoModel();
    }

    /**
     * @param $data
     * @param int $num
     * @return array
     */
    public function getPaginateList($data, $num = 10)
    {
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getPaginateList($likeKeys, $data, $field = '*', $num);
            $result = $list->toArray();
        } catch (\Exception $e) {
            $result = Arr::getPaginateDefaultData($num);
        }
        return $result;
    }

    /**
     * @param $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalById($id)
    {
        $res = $this->model->getById($id);
        if (!$res) {
            return [];
        }
        return $res->toArray();
    }

    /**
     * 插入数据
     * @param $data
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function insertData($data)
    {
        try {
            $id = $this->add($data);
        } catch (\Exception $e) {
            throw new Exception('数据库内部异常');
        }
        $result = [
            'id' => $id
        ];
        return $result;
    }

    /**
     * @param $data
     * @return \think\Collection
     * @throws \Exception
     */
    public function insertAll($data)
    {
        return $res = $this->model->saveAll($data);
    }

    /**
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getLimit()
    {
        $res = $this->model->getLimit();
        if (!$res) {
            return [];
        }
        return $res->toArray();
    }

    /**
     * 同步数据
     * @return bool|\think\response\Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function videoSync()
    {
        $config = config('video');
        $secrctId = $config['secret_id'];
        $secrctKey = $config['secret_key'];
        $url = $config['url'];
        try {
            $cred = new Credential($secrctId, $secrctKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint($url);

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, "", $clientProfile);

            $req = new SearchMediaRequest();
            $params = ["Limit" => 50, "Offest" => 0, "Categories" => ["Video"]];
            //			$params = [];
            $req->fromJsonString(json_encode($params));
            $resp = $client->SearchMedia($req);

            $videoList = json_decode($resp->toJsonString(), true);
        } catch (TencentCloudSDKException $e) {
            return Show::error($e->getMessage());
        }

        $list = $this->getLimit();
        $ids = array_column($list, 'vid');
        $insertData = [];
    
        foreach ($videoList['MediaInfoSet'] as $item) {
            $baseInfo = $item['BasicInfo'];
            $createTime = strtotime($baseInfo['CreateTime']);
            $pathInfo = pathinfo($baseInfo['MediaUrl']);
            if (!in_array($pathInfo['extension'], ['mp4'])) {
                continue;
            }
            if (in_array($baseInfo['Vid'], $ids)) {
                continue;
            }
            $insertData = [
                'title' => $baseInfo['Name'],
                'vid' => $baseInfo['Vid'],
                'class_id' => $baseInfo['ClassId'],
                'media_url' => $baseInfo['MediaUrl'],
                'cover_url' => $baseInfo['CoverUrl'],
                'upload_time' => $createTime,
            ];
            $this->syncAdd($insertData);
        }

//        $this->insertAll($insertData);

        return true;
    }
}
