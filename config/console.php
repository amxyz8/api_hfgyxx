<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'repair' => 'app\api\command\Repair',
        'lost' => 'app\api\command\Lost',
        'lottery' => 'app\api\command\Lottery',
        'rss_sync' => 'app\command\RssSync',
        'news_sync' => 'app\command\NewsSync',
        'video_sync' => 'app\command\VideoSync',
    ],
];
