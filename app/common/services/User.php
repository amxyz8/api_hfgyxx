<?php


namespace app\common\services;

use app\common\lib\Str;
use app\common\lib\Time;
use app\common\model\User as UserModel;
use think\Exception;
use think\facade\Log;

class User extends BaseServices
{
    public $model = null;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * @param $data
     * @return array|bool
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login($data)
    {
        $redisCode = cache(config('redis.code_pre').$data['phone_number']);
        if (empty($redisCode) || $redisCode != $data['code']) {
            throw new Exception('不存在该验证码', -1009);
        }
        
        $user = $this->model->getUserByPhoneNumber($data['phone_number']);
        if (!$user) {
            $username = 'test'.$data['phone_number'];
            $userData = [
                'openid' => '123',
                'username' => $username,
                'phone_number' => $data['phone_number'],
                'type' => $data['type'],
                'status' => config('status.mysql.table_normal'),
            ];
            try {
                $this->model->save($userData);
                $userId = $this->model->id;
            } catch (\Exception $e) {
                Log::error('/api/login 错误:' . $e->getMessage());
                throw new Exception('数据库内部异常');
            }
        } else {
            //更新
            $userId = $user->id;
            $username = $user->nickname;
        }
        $token = Str::getLoginToken($data['phone_number']);
        $redisData = [
            'id' => $userId,
            'username' => $username,
        ];
        $res = cache(config('rediskey.token_pre').$token, $redisData, Time::userLoginExpiresTime(2));
        
        return $res ? ['token' => $token, 'username' => $username] : false;
    }

    /**
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalUserById($id)
    {
        $user = $this->model->getUserById($id);
        if (!$user || $user->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $user->toArray();
    }
    
    /**
     * @param $openid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalUserByOpenId($openid)
    {
        $user = $this->model->getUserByOpenid($openid);
        if (!$user || $user->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $user->toArray();
    }

    /**
     * @param $username
     * @return array
     */
    public function getNormalUserByUsername($username)
    {
        $user = $this->model->getUserByUesrname($username);
        if (!$user || $user->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $user->toArray();
    }

    /**
     * @param $username
     * @return array
     */
    public function getUserByNames($username)
    {
        return $this->model->getUserByNames($username);
    }

    /**
     * @param $number
     * @return array
     */
    public function getNormalUserByNumber($number)
    {
        $user = $this->model->getUserByNumber($number);
        if (!$user || $user->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $user->toArray();
    }

    /**
     * @param $nums
     * @return array
     */
    public function getNormalUserByNumbers($nums)
    {
        $user = $this->model->getUserByNumbers($nums);
        if (!$user) {
            return [];
        }
        return $user->toArray();
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update($id, $data)
    {
        $user = $this->getNormalUserById($id);
        if (!$user) {
            throw new Exception('不存在该用户');
        }

        //redis需要同步
        return $this->model->updateById($id, $data);
    }

    /**
     * 根据用户ids获取用户列表
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserByIds($id)
    {
        $user = $this->model->getUserByIds($id);
        if (!$user) {
            return [];
        }
        return $user->toArray();
    }
}
