<?php

/**
 * redis key
 */
return [
    'token_pre' => 'token_pre_',

    //失物招领延迟队列
    'lost_status_key' => 'lost_status',
    'lost_expire' => 7*60*60,


    //抽奖
    'lottery_by_id' => 'lottery_by_id_',
    'lottery_incr_by_id' => 'lottery_incr_by_id_',
    'lottery_status_key' => 'lottery_status',

    //薪资管理
    'salary_password' => 'salary_password',
];
