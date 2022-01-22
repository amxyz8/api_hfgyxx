<?php


namespace app\common\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;
use think\model\concern\SoftDelete;

class Banner extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $hidden = [
        'create_time',
        'update_time',
        'delete_time',
    ];
    /**
     * @param string $where
     * @param string $field
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getNormalBanners($where, $field = "*")
    {
        $order = [
            "sequence" => "desc",
            "id" => "desc",
        ];
        $result = $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
        
        return $result;
    }
    
    /**
     * @param $id
     * @return array|bool|Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getBannerById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        return $this->find($id);
    }
    
    /**
     * 获取列表数据
     * @param string $field
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getLists($field = '*', $num = 10)
    {
        $order = [
            "sequence" => "desc",
            "id" => "desc"
        ];
        $result = $this->where("status", "<>", config("status.mysql.table_delete"))
            ->field($field)
            ->order($order)
            ->paginate($num);
        //echo $this->getLastSql();exit;
        return $result;
    }
    
    /**
     * 根据主键ID更新数据表中的数据
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateById($id, $data)
    {
        $id = intval($id);
        if (empty($id) || empty($data) || !is_array($data)) {
            return false;
        }
        
        $where = [
            "id" => $id,
        ];
        
        return $this->where($where)->save($data);
    }
}
