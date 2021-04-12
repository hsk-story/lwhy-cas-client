<?php

return [

    //项目代码
    'project_code' => 'llk',

    //项目密钥
    'secret' => 'bCQwEnbn85MGtt3A0RhEuRYuF9GF3KhS',

    //cas服务器地址
    'cas_server' => 'http://192.168.66.102:8800/',

    //缓存类型, file 或 redis
    'cache' => 'file',

    //用户认证相关
    'auth' => [
        'guard_name' => 'cas',
        'guard' => [
            'driver' => 'token',
            'provider' => 'cas_users',
            'hash' => false,
            'input_key' => 'api_token',
            'storage_key' => 'api_token',
        ],
        'provider' => [
            'driver' => 'cas_client',
            'token_key' => 'api_token',
        ]
    ]
];