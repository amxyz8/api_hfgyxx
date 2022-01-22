<?php

/**
 * 微信公众号相关配置
 */
return [
        'app_id' => 'wx1f35328a029b8c68',         // AppID
        'secret' => '6897b9c79cb11d99018805c8860a9725',    // AppSecret
        'token' => '',           // Token
        'aes_key' => '',   // EncodingAESKey

        /*
         * OAuth 配置
         *
         * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
         * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
         */
        'oauth' => [
            'scopes'   => array_map('trim', explode(',', 'snsapi_userinfo')),
            'callback' => '/api/wechat/token',
        ],
];
