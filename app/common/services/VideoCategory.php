<?php


namespace app\common\services;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\model\VideoCategory as VideoCategoryModel;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\DescribeAllClassRequest;
use TencentCloud\Vod\V20180717\VodClient;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\route\Rule;

class VideoCategory extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new VideoCategoryModel();
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
     * @param string $field
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList($field = '*')
    {
        $res = $this->model->getList($field);
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
     * 同步数据
     * @return bool|\think\response\Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function cateSync()
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

            $req = new DescribeAllClassRequest();

            $params = array(

            );
            $req->fromJsonString(json_encode($params));

            $resp = $client->DescribeAllClass($req);

            $cateList = json_decode($resp->toJsonString(), true);
        } catch (TencentCloudSDKException $e) {
            return Show::error($e->getMessage());
        }

        $list = $this->getList('*');
        $ids = array_column($list, 'class_id');

        $insertData = [];

        foreach ($cateList['ClassInfoSet'] as $item) {
            if (!$item['ClassId']) {
                continue;
            }
            if (in_array($item['ClassId'], $ids)) {
                continue;
            }
            $insertData[] = [
                'class_id' => $item['ClassId'],
                'class_name' => $item['ClassName'],
                'level' => $item['Level'],
            ];
        }

        $this->insertAll($insertData);

        return true;
    }
}
