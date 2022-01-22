<?php

return [
    'success' => 1,
    'error' => 0,
    'not_login' => -1,
    'user_is_register' => -2,
    'action_not_found' => -3,
    'method_not_found' => -4,
    'name_not_null' => -5,
    'not_bind' => -6,

    // mysql相关的状态配置
    "mysql" => [
        "table_normal" => 1, // 正常
        "table_end" => 2, // 已结束
        "table_pedding" => 0, // 待审核
        "table_delete" => 99, // 删除
    ],

    //注册账号, number白名单
    "white_list" => ['00100']
];
