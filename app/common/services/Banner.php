<?php


namespace app\common\services;

use app\common\model\Banner as BannerModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

class Banner extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new BannerModel();
    }
    
    /** 插入数据
     * @param $data
     * @return array
     * @throws Exception
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function insertData($data)
    {
        $data['is_show'] = $data['is_show']??1;
        try {
            $id = $this->add($data);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
        return ['id' => $id];
    }
    
    /**
     * 获取列表数据
     * @param $num
     * @return array
     */
    public function getLists($num)
    {
        $field = 'id, img_url, is_show, sequence';
        $list = $this->model->getLists($field, $num);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
    }

    /**
     * 获取列表数据
     * @param $where
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalList($where)
    {
        $field = 'id, img_url, sequence';
        $list = $this->model->getNormalBanners($where, $field);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        return $result;
    }
    
    /**
     * @param $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalBannerById($id)
    {
        $res = $this->model->getBannerById($id);
        if (!$res || $res->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $res->toArray();
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
        $res = $this->getNormalBannerById($id);
        if (!$res) {
            throw new Exception("数据不存在");
        }
        return $this->model->updateById($id, $data);
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
        $cate = $this->getNormalBannerById($id);
        if (!$cate) {
            throw new Exception("数据不存在");
        }
        
        $data = [
            'status' => config('status.mysql.table_delete')
        ];
        
        return $this->model->updateById($id, $data);
    }
}
