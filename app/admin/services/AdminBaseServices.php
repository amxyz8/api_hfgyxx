<?php


namespace app\admin\services;

use think\facade\Log;

class AdminBaseServices
{
    /**
     * 新增
     * @param $data
     * @return int
     */
    public function add($data)
    {
        if (!isset($data['status'])) {
            $data['status'] = config("status.mysql.table_normal");
        }
        try {
            $this->model->save($data);
        } catch (\Exception $e) {
            Log::error('错误信息:' . $e->getMessage());
            return 0;
        }

        return $this->model->id;
    }
}
