<?php

/**
 * 微信公众号相关配置
 */
return [
        'list' => [
            [
                'id' => 1,
                'icon' => 'el-icon-s-home',
                'value' => '微主页',
                'child' => [
                    ['id' => 11, 'value' => '菜单管理', 'index' => '/menu'],
                    ['id' => 12, 'value' => '轮播管理', 'index' => '/banner'],
                    ['id' => 13, 'value' => '文章管理', 'index' => '/article'],
                    ['id' => 14, 'value' => '佳作赏析', 'index' => '/excellent'],
//			        ['id' => 15, 'value' => '校园风光', 'index' => '/scenery'],
                    ['id' => 16, 'value' => '学校视频', 'index' => '/video'],
                    ['id' => 17, 'value' => '报名管理', 'index' => '/apply'],
                ],
            ],
            [
                'id' => 2,
                'icon' => 'el-icon-s-custom',
                'value' => '微服务',
                'child' => [
                    ['id' => 21, 'value' => '掌上报修', 'index' => '/service'],
//			        ['id' => 22, 'value' => '工资管理', 'index' => '/wage'],
                    ['id' => 23, 'value' => '监考信息', 'index' => '/proctor'],
                ],
            ],
            [
                'id' => 3,
                'icon' => 'el-icon-menu',
                'value' => '微生活',
                'child' => [
                    ['id' => 31, 'value' => '问卷调查', 'index' => '/survey'],
                    ['id' => 32, 'value' => '活动抽奖', 'index' => '/smoke'],
                    ['id' => 33, 'value' => '评比评选', 'index' => '/compare'],
                    ['id' => 34, 'value' => '失物招领', 'index' => '/found'],
                ],
            ],
            [
                'id' => 4,
                'icon' => 'el-icon-s-platform',
                'value' => '系统管理',
                'child' => [
                    ['id' => 41, 'value' => '用户管理', 'index' => '/user'],
                    ['id' => 42, 'value' => '部门管理', 'index' => '/tissue'],
                    ['id' => 43, 'value' => '人员管理', 'index' => '/crew'],
                ],
            ],
            [
                'id' => 5,
                'icon' => 'el-icon-money',
                'value' => '薪资管理',
                'child' => [
                    ['id' => 22, 'value' => '工资管理', 'index' => '/wage'],
                ],
            ],
        ]
];
