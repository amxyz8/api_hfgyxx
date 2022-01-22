<?php


namespace app\common\services;

use app\admin\services\AdminUser as AdminUserService;
use app\common\lib\Arr;
use app\common\model\News as NewsModel;
use GuzzleHttp\Client;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Log;

class News extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new NewsModel();
    }

    /**
     * @param $data
     * @param $num
     * @return array
     * @throws DbException
     */
    public function getPaginateList($data, $num)
    {
        $field = 'id, small_title, cate_id, title, is_top, is_hot, status, img_urls, cover_url, desc, pub_date';
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getPaginateList($likeKeys, $data, $field = '*', $num);
            $result = $list->toArray();
            if ($result['data']) {
                $color = ['#7cc623', '#5e8ac6', '#e73c2f', '#3eeac7'];
                $cateIds = array_column($result['data'], 'cate_id');
                $cateNames = (new Category())->getCateByIds($cateIds);
                foreach ($result['data'] as $key => &$data) {
                    $data['color'] = $color[$key % 4];
                    $data['img_urls'] = json_decode($data['img_urls'], true);
                    $data['cover_url'] = json_decode($data['cover_url'], true);
                    $data['cate_name'] = $cateNames[$data['cate_id']]['name'];
                }
            }
        } catch (\Exception $e) {
            $result = Arr::getPaginateDefaultData($num);
        }
        return $result;
    }
    
    public function getByWhere($data)
    {
        return $this->model->getByCondition($data);
    }

    /**
     * @param $data
     * @param $num
     * @return array
     * @throws DbException
     */
    public function getVideoPaginateList($data, $num)
    {
        $field = 'id, small_title, cate_id, title, is_top, is_hot, status, img_urls, cover_url, desc, create_time';
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getVideoPaginateList($likeKeys, $data, $field = '*', $num);
            $result = $list->toArray();
            if ($result['data']) {
                $color = ['#7cc623', '#5e8ac6', '#e73c2f', '#3eeac7'];
                $cateIds = array_column($result['data'], 'cate_id');
                $cateNames = (new Category())->getCateByIds($cateIds);
                foreach ($result['data'] as $key => &$data) {
                    $data['color'] = $color[$key % 4];
                    $data['img_urls'] = json_decode($data['img_urls'], true);
                    $data['cover_url'] = json_decode($data['cover_url'], true);
                    $data['cate_name'] = $cateNames[$data['cate_id']]['name'];
                }
            }
        } catch (\Exception $e) {
            $result = Arr::getPaginateDefaultData($num);
        }
        return $result;
    }
    
    public function getNormalAllNews()
    {
        $field = "id, small_title, title, is_top, is_hot, status, img_urls, cover_url, desc, create_time";
        try {
            $res = $this->model->getNormalNews($field, 10);
        } catch (\Exception $e) {
            Log::error('getNormalAllNews 错误:' . $e->getMessage());
            throw new Exception('数据库内部异常');
        }
        
        if (!$res) {
            return $res;
        }
        $res = $res->toArray();
        foreach ($res as &$re) {
            $re['img_urls'] = json_decode($re['img_urls'], true);
            $re['cover_url'] = json_decode($re['cover_url'], true);
        }
        return $res;
    }

    /**
     * 返回正常数据
     * @param $title
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalNewsByName($title)
    {
        $res = $this->model->getNewsByTitle($title);
        if (!$res || $res->status != config("status.mysql.table_normal")) {
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
            if (isset($data['content'])) {
                $cData = [
                    'news_id' => $id,
                    'content' => $data['content'],
                    'create_time' => $data['create_time'],
                    'update_time' => $data['update_time'],
                ];
                $this->model->NewsContent()->insert($cData);
            }
        } catch (\Exception $e) {
            throw new Exception('数据库内部异常');
        }
        $result = [
            'id' => $id
        ];
        return $result;
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
    public function insertSyncData($data)
    {
        try {
            $id = $this->syncAdd($data);
            if (isset($data['content'])) {
                $cData = [
                    'news_id' => $id,
                    'content' => $data['content'],
                    'create_time' => $data['create_time'],
                    'update_time' => $data['update_time'],
                ];
                $this->model->NewsContent()->insert($cData);
            }
        } catch (\Exception $e) {
            throw new Exception('数据库内部异常');
        }
        $result = [
            'id' => $id
        ];
        return $result;
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function update($id, $data)
    {
        $news = $this->getNormalNewsById($id);
        if (!$news) {
            throw new Exception("数据不存在");
        }

        //检查名称是否存在
        $result = [];
        if (isset($data['title'])) {
            $result = $this->getNormalNewsByTitle($data['title']);
        }
        if ($result && $result['id'] != $id) {
            throw new Exception("标题不可重复");
        }

        try {
            if (isset($data['content']) && !empty($data['content'])) {
                $res = $this->model->updateContentRelation($id, $data);
            } else {
                if (isset($data['content'])) {
                    unset($data['content']);
                }
                $res = $this->model->updateById($id, $data);
            }
        } catch (\Exception $e) {
            Log::error('service/news/update 错误:' . $e->getMessage());
            throw new Exception('数据库内部异常');
        }
        return $res;
    }

    /**
     * @param $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalNewsById($id)
    {
        $news = $this->model->getNewsById($id);
        if (!$news || $news->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $news->toArray();
    }

    /**
     * 格式化news结果集
     * @param $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function formatNews($id)
    {
        $result = $this->getNormalNewsById($id);
        if (!$result) {
            return [];
        }

        $cates = (new Category())->getCateByIds(array($result['cate_id']));
        $users = (new AdminUserService())->getAdminUserByIds(array($result['user_id']));
        $result['cate_name'] = $cates[$result['cate_id']]['name']??'';
        $result['cate_path'] = empty($cates[$result['cate_id']]['pid'])?[$result['cate_id']]:[$cates[$result['cate_id']]['pid'], $result['cate_id']];
        $result['user_name'] = $users[$result['user_id']]??'';
        $result['content'] = $result['newsContent']['content']??'';
        $result['img_urls'] = json_decode($result['img_urls']);
        $result['cover_url'] = json_decode($result['cover_url']);
        unset($result['newsContent']);

        if ($result['content']) {
//            $preg = "/style(.*?)\"/si";
//            $preg = "/(style)=\"[\s\S]*?\"/i";
            $preg = "/text-indent(.*?);/i";
            $result['content'] = preg_replace($preg, "text-indent:2em;", $result['content']);
            $preg = "/font-family(.*?);/i";
            $result['content'] = preg_replace($preg, "", $result['content']);
            $preg = "/font-size(.*?);/i";
            $result['content'] = preg_replace($preg, "", $result['content']);
            $preg = "/background(.*?);/i";
            $result['content'] = preg_replace($preg, "", $result['content']);
            $preg = "/margin-left(.*?);/i";
            $result['content'] = preg_replace($preg, "", $result['content']);
            $preg = "/<img.*?>/i";
            $result['content'] = preg_replace($preg, "<p class=\"text_img\">$0</p>", $result['content']);
        }
        
        return $result;
    }

    /**
     * @param $title
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalNewsByTitle($title)
    {
        $res = $this->model->getNewsByTitle($title);
        if (!$res || $res->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $res->toArray();
    }
    
    /**
     * @param int $cateId
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getLimitByCateId($cateId = 0)
    {
        $res = $this->model->getLimitByCateId($cateId);
        if (!$res) {
            return [];
        }
        return $res->toArray();
    }

    /**
     * @param $id
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function delete($id)
    {
        $data = [
            'status' => config('status.mysql.table_delete')
        ];

        return $this->model->deleteById($id);
    }

    /**
     * 同步校方新闻数据
     * @param $cateId
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function rssSync($cateId)
    {
        if ($cateId == 2) {//校园新闻
            $xmlStr = file_get_contents('http://www.hfgyxx.com/rss/news_10601_1060108.xml');
        } else {//通知公告
            $xmlStr = file_get_contents('http://www.hfgyxx.com/rss/news_10601_1060107.xml');
        }
        $obj = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $eJSON = json_encode($obj);
        $dJSON = json_decode($eJSON, true);
        $data = [];
        $newsList = (new News())->getLimitByCateId($cateId);
        $nums = array_column($newsList, 'xwbh');
        
        foreach ($dJSON['channel']['item'] as $key => $value) {
            if ($key == 45) {
                break;
            }
            if (in_array($value['xwbh'], $nums)) {
                continue;
            }
            if ($value['xwbh'] == '159367545322075703') {//通知公告置顶新闻过滤
                continue;
            }
            $link = $value['link'];
            $htmlStr = file_get_contents($link);
            $htmlStr = mb_convert_encoding($htmlStr, "utf-8", "gbk");
            $pattern = '/<div class="xwcon" id="xwcontentdisplay">(.+?)<\/div>/is';
            preg_match($pattern, $htmlStr, $match);
            $content = $match[1]??'';
            $url = $value['enclosure']["@attributes"]['url']??'';
            $url = array($url);
            $temp = [
                'title' => $value['title'],
                'desc' => $value['description'],
                'cate_id' => $cateId,
                'xwbh' => $value['xwbh'],
                'img_urls' => json_encode($url),
                'pub_date' => strtotime($value['pubDate']),
                'user_id' => 1,
                'content' => $content,
                'create_time' => time(),
                'update_time' => time()
            ];
            $id = $this->insertSyncData($temp);
        }
        return true;
    }
    
    /**
     * 同步校派小程序新闻数据
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function newsSync1()
    {
        $config = config('news');
        $cateKeys = array_keys($config['news1']);
        $client = new Client();
        foreach ($cateKeys as $cateId) {
            $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/index', [
                'query' => [
                    'schoolid' => 23,
                    'mark' => $cateId,
                ]
            ]);
            $body = json_decode($response->getBody()->getContents(), true);
            foreach ($config['news1'][$cateId] as $tempId) {
                $key = 'mark'.$tempId;
                foreach ($body[$key] as $value) {
                    $response = curl_get("https://highschool.schoolpi.net/api/vocational_lists/view?schoolid=23&id={$value['id']}");
                    $detail = json_decode($response, true);
                    $record = (new News())->getByWhere(['cate_id' => $config['one'][$tempId], 'title' => $value['title']]);
                    if ($record) {
                        continue;
                    }
                    $temp = [
                        'title' => $value['title'],
                        'cate_id' => $config['one'][$tempId],
                        'img_urls' => json_encode($value['thumb']),
                        'pub_date' => strtotime($value['create_time']),
                        'user_id' => 1,
                        'content' => "",
                        'create_time' => time(),
                        'update_time' => time(),
                        'read_count' => $detail['data']['hits']??rand(30, 100),
                    ];
                    $temp['content'] = $detail['code'] == 1 ? $detail['data']['content'] : "";
                    //				    array_push($data, $temp);
//            (new News())->insertData($temp);
                    (new News())->insertSyncData($temp);
//            return Show::success(['data' => $body['data']]);
                }
            }
        }
        return true;
    }
    
    /**
     * 同步校派小程序新闻数据
     * @param $cateId
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function newsSync2()
    {
        $config = config('news');
        $cateKeys = array_keys($config['news2']);
        $data = [];
        $client = new Client();
        foreach ($cateKeys as $cateId) {
            $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/index', [
                'query' => [
                    'schoolid' => 23,
                    'mark' => $cateId,
                ]
            ]);
            $body = json_decode($response->getBody()->getContents(), true);
            foreach ($body['data'] as $value) {
                if (!$value['title']) {
                    continue;
                }
                $response = curl_get("https://highschool.schoolpi.net/api/vocational_lists/view?schoolid=23&id={$value['id']}");
                $detail = json_decode($response, true);
                $record = (new News())->getByWhere(['cate_id' => $config['news2'][$cateId], 'title' => $value['title']]);
                if ($record) {
                    continue;
                }
                
                $thumb = is_array($value['thumb']) ? json_encode($value['thumb']) : json_encode(array($value['thumb']));
                $temp = [
                    'title' => $value['title'],
                    'cate_id' => $config['news2'][$cateId],
                    'img_urls' => $thumb,
                    'pub_date' => strtotime($value['create_time']),
                    'user_id' => 1,
                    'content' => "",
                    'create_time' => time(),
                    'update_time' => time(),
                    'read_count' => $value['hits']??rand(30, 100),
                ];
                $temp['content'] = $detail['code'] == 1 ? $detail['data']['content'] : "";
                //			    array_push($data, $temp);
                (new News())->insertSyncData($temp);
            }
        }
        return true;
    }
    
    /**
     * 同步校派小程序新闻数据
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function newsSync3()
    {
        $config = config('news');
        $cateKeys = array_keys($config['news3']);
        $cateId = current($cateKeys);
        $data = [];
        $client = new Client();
        $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/index', [
            'query' => [
                'schoolid' => 23,
                'mark' => $cateId,
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        foreach ($body['data'] as $value) {
            if (!$value['title']) {
                continue;
            }
            $response = curl_get("https://highschool.schoolpi.net/api/vocational_lists/view?schoolid=23&id={$value['id']}");
            $detail = json_decode($response, true);
            $record = (new News())->getByWhere(['cate_id' => current($config['news3']), 'title' => $value['title']]);
            if ($record) {
                continue;
            }
            
            $thumb = is_array($value['thumb']) ? json_encode($value['thumb']) : json_encode(array($value['thumb']));
            $temp = [
                'title' => $value['title'],
                'cate_id' => current($config['news3']),
                'img_urls' => $thumb,
                'pub_date' => strtotime($detail['data']['create_time']),
                'user_id' => 1,
                'content' => "",
                'create_time' => time(),
                'update_time' => time(),
                'read_count' => $detail['data']['hits']??rand(30, 100),
            ];
            $temp['content'] = $detail['code'] == 1 ? $detail['data']['content'] : "";
            //			    array_push($data, $temp);
            (new News())->insertSyncData($temp);
        }
        return true;
    }
    
    /**
     * 同步校派小程序新闻数据
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function newsSync4()
    {
        $config = config('news');
        $cateKeys = array_keys($config['news4']);
        $cateId = current($cateKeys);
        $data = [];
        $client = new Client();
        $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/index', [
            'query' => [
                'schoolid' => 23,
                'mark' => $cateId,
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        foreach ($body['data'] as $value) {
            if (!$value['name']) {
                continue;
            }
            $record = (new News())->getByWhere(['cate_id' => current($config['news4']), 'title' => $value['name']]);
            if ($record) {
                continue;
            }
            
            $thumb = is_array($value['thumb']) ? json_encode($value['thumb']) : json_encode(array($value['thumb']));
            $temp = [
                'title' => $value['name'],
                'cate_id' => current($config['news4']),
                'img_urls' => $thumb,
                'user_id' => 1,
                'create_time' => time(),
                'update_time' => time(),
                'pub_date' => strtotime($value['create_time']),
                'read_count' => $value['hits']??rand(30, 100),
            ];
            //		    array_push($data, $temp);
            (new News())->insertSyncData($temp);
        }
        return true;
    }

    /**
     * 更新阅读人数
     * @param $id
     * @return bool
     */
    public function updateReadCount($id)
    {
        $this->model->incCount($id, 1);
        return true;
    }
}
