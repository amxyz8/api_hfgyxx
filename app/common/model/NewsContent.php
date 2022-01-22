<?php


namespace app\common\model;

class NewsContent extends BaseModel
{
    public $allowField = [
        'content',
    ];

    /**
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalCategorys($field = "*")
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];
        
        $order = [
            "sequence" => "desc",
            "id" => "desc"
        ];
        $result = $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
        
        return $result;
    }
    
    /**
     * 根据name查询数据
     * @param $name
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCateByName($name)
    {
        if (empty($name)) {
            return false;
        }
        
        $where = [
            'name' => $name
        ];
        
        return $this->where($where)->find();
    }
    
    /**
     * @param $id
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCateById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        return $this->find($id);
    }
}
