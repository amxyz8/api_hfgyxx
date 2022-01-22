<?php


namespace app\common\services;

use think\facade\Log;

class BaseServices
{
    /**
     * 新增
     * @param $data
     * @return int
     */
    public function add($data)
    {
        $data['status'] = config("status.mysql.table_normal");
        try {
            $this->model->save($data);
        } catch (\Exception $e) {
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }
        // // 返回主键ID
        return $this->model->id;
    }

    /**
     * 异步同步数据新增
     * @param $data
     * @return int
     */
    public function syncAdd($data)
    {
        $data['status'] = config("status.mysql.table_normal");
        try {
            $res = $this->model->strict(false)->insertGetId($data);
        } catch (\Exception $e) {
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }
        return $res;
    }

    /**
     * 批量新增
     * @param $data
     * @return bool|int
     */
    public function addAll($data)
    {
        try {
            $res = $this->model->saveAll($data);
        } catch (\Exception $e) {
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool|int
     */
    public function del($id)
    {
        try {
            $model = $this->model->find($id);
            $res = $model->delete();
        } catch (\Exception $e) {
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool|int
     */
    public function delAll($id)
    {
        try {
            $model = $this->model->find($id);
            $res = $model->delete();
        } catch (\Exception $e) {
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }

        return true;
    }
}
