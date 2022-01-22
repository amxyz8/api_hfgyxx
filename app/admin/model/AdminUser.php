<?php


namespace app\admin\model;

use app\common\model\BaseModel;
use think\db\exception\DbException;

class AdminUser extends BaseModel
{
    protected $type = [
        'last_login_time' => 'timestamp',
    ];
    protected $hidden = [
        'password',
        'operate_user',
        'create_time',
        'update_time',
    ];
    /**
     * 根据id获取用户信息
     * @param $id 用户id
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserById($id)
    {
        if (empty($id)) {
            return false;
        }

        $where = [
            'id' => $id
        ];

        return $this->where($where)->find();
    }
    
    /**
     * 根据username获取用户信息
     * @param $username 用户名称
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserByUserName($username)
    {
        if (empty($username)) {
            return false;
        }
        
        $where = [
            'username' => $username
        ];
        
        return $this->where($where)->find();
    }
    
    /**
     * 根据ids获取用户信息
     * @param array $ids
     * @return bool|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserByIds($ids = [])
    {
        if (empty($ids)) {
            return false;
        }
        
        return $this->whereIn('id', $ids)->select();
    }

    /**
     * @param $where
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getPaginateLists($where, $num = 10)
    {
        $order = [
            "id" => "desc"
        ];
        $result = $this->where($where)->order($order)->paginate($num);
//        echo $this->getLastSql();exit;
        return $result;
    }

    /**
     * @param $where
     * @param int $num
     * @return \think\Paginator
     * @throws DbException
     */
    public function getList($where)
    {
        $order = [
            "id" => "desc"
        ];
        $result = $this->getByCondition($where, $order);
//        echo $this->getLastSql();exit;
        return $result;
    }

//    /**
//     * 根据主键ID更新数据表中的数据
//     * @param $id
//     * @param $data
//     * @return bool
//     */
//    public function deleteById($id, $data)
//    {
//        if (empty($id) || empty($data) || !is_array($data)) {
//            return false;
//        }
//
//        $where = [];
//
//        if (is_array($id)) {
//            $where[] = ['id', 'in', $id];
//        } else {
//            $where[] = ['id', '=', intval($id)];
//        }
//
//        return $this->where($where)->save($data);
////        echo $this->getLastSql();exit();
//    }
}
