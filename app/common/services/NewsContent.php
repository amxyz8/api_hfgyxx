<?php


namespace app\common\services;

use app\common\model\News as NewsModel;
use think\Exception;
use think\facade\Log;

class NewsContent extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new NewsModel();
    }
    
    public function getNormalAllCategorys()
    {
        $field = "id, name, pid";
        try {
            $categorys = $this->model->getNormalCategorys($field);
        } catch (\Exception $e) {
            Log::error('getNormalAllCategorys 错误:' . $e->getMessage());
            throw new Exception('数据库内部异常');
        }
        
        if (!$categorys) {
            return $categorys;
        }
        $categorys = $categorys->toArray();
        return $categorys;
    }

    /**
     * 返回正常数据
     * @param $name
     * @return array
     */
    public function getNormalCateByName($name)
    {
        $cate = $this->model->getCateByName($name);
        if (!$cate || $cate->status != config("status.mysql.table_normal")) {
            return [];
        }
        return $cate->toArray();
    }
    
    /** 插入数据
     * @param $data
     * @return array
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function insertData($data)
    {
        $cateResult = $this->getNormalCateByName($data['name']);
        if ($cateResult) {
            throw new Exception("数据已存在");
        }
        
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
}
