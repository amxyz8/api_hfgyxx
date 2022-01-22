<?php

/**
 * 微信公众号相关配置
 */
return [
    'app_id' => 'wx1f35328a029b8c68',         // AppID
    'app_secret' => '6897b9c79cb11d99018805c8860a9725',    // AppSecret
    
    //获取code
    'get_code_url' => "https://open.weixin.qq.com/connect/oauth2/authorize?" .
            "appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect",
    'redirect_uri' => '/api/token/code',

    //获取token
    'get_token_url' => "https://api.weixin.qq.com/sns/oauth2/access_token?" .
            "appid=%s&secret=%s&code=%s&grant_type=authorization_code",

    //获取access_token
    'get_at_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
        "grant_type=client_credential&appid=%s&secret=%s",

    //获取自定义菜单
    'get_custom_menu' => "https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?" .
        "access_token=%s",

    //创建自定义菜单
    'create_menu' => "https://api.weixin.qq.com/cgi-bin/menu/create?" .
        "access_token=%s",

    //获取微信关注用户列表
    'user_list' => "https://api.weixin.qq.com/cgi-bin/user/get?" .
        "access_token=%s&next_openid=%s",
    
    //模板消息推送
    'template_url' => "https://api.weixin.qq.com/cgi-bin/message/template/send?" .
        "access_token=%s",

    'api_token_pre' => 'api_token_',
    //随机秘钥
    'token_salt' => 'WYMYyEx4FvWw5ioc',
    //过期时间
    'token_expire_in' => 24*60*60,
];
