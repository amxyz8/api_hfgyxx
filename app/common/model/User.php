<?php


namespace app\common\model;

use think\model\concern\SoftDelete;

class User extends BaseModel
{
    use SoftDelete;

    protected $hidden = [
        'create_time',
        'update_time',
        'delete_time'
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    /**
     * 根据openid获取用户信息
     * @param $openid 微信openid
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserByOpenid($openid)
    {
        if (empty($openid)) {
            return false;
        }

        $where = [
            'openid' => $openid
        ];

        return $this->where($where)->find();
    }

    /**
     * 根据phoneNumber获取用户信息
     * @param $openid 微信openid
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserByPhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return false;
        }

        $where = [
            'phone_number' => $phoneNumber
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
    public function getUserById($id)
    {
        $id = intval($id);
        if (!$id) {
            return false;
        }
        return $this->find($id);
    }

    /**
     * @param $id
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserByIds($id)
    {
        if (!$id) {
            return false;
        }
        $result = $this->whereIn('id', $id)->select();
//        echo $this->getLastSql();exit;
        return $result;
    }

    public function getUserByUesrname($username)
    {
        if (empty($username)) {
            return false;
        }

        $where = [
            'nickname' => $username
        ];

        return $this->where($where)->find();
    }

    public function getUserByNames($username)
    {
        if (empty($username)) {
            return false;
        }

        return $this->whereIn('username', $username)->select()->toArray();
    }

    public function getUserByNumber($number)
    {
        if (empty($number)) {
            return false;
        }

        $where = [
            'number' => $number
        ];

        return $this->where($where)->find();
    }

    /**
     * @param $nums
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserByNumbers($nums)
    {
        $field = 'id, username';
        if (!$nums) {
            return false;
        }
        $result = $this->whereIn('number', $nums)->field($field)->select();
//        echo $this->getLastSql();exit;
        return $result;
    }

    public function updateById($id, $data)
    {
        $id = intval($id);
        if (empty($id) || empty($data) || !is_array($data)) {
            return false;
        }
        $where = [
            'id' => $id
        ];
        return $this->where($where)->save($data);
    }
}
