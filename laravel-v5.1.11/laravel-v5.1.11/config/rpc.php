<?php

return [
    'client' => [
//        'message-service' => [
//            'type'     => 'http',
//            'protocol' => 'jsonrpc',
//            'conf'     => [
//                'url' => env('DOMAIN_MESSAGE_SERVICE') . '/rpc', // 指向服务端配置的rpc入口
//            ],
//            'route'    => [
//                'queue' => [                       // 本地调用的方法名
//                    'sModule'  => 'MsgQueueController', // 对应rpc服务端的模块名
//                    'sMethod'  => 'store',       // 对应rpc服务端的方法名
//                    'sVersion' => '1.0',           // 版本号
//                ]
//            ],
//            'accessor' => '\\App\\Services\\Accessors\\EstateServiceAccessorWithRPC',
//        ],
        'loupan-service'  => [
            'type'     => 'http',
            'protocol' => 'jsonrpc',
            'conf'     => [
                //'url' =>  'http://localhost:81/www/item-test/lightservice/example/http/server.php?auth=iamauth',//hf_url('loupanService', '/rpc'),
                'url' =>  'http://localhost:81/user-service/public/rpc.php',
            ],
            'route'    => [
                'getList'                => [
                    'sModule'  => 'LoupanController',
                    'sMethod'  => 'getList',
                    'sVersion' => '1.0',
                ],
                'getOrder'                => [
                    'sModule'  => 'LoupanController',
                    'sMethod'  => 'getOrder',
                    'sVersion' => '1.0',
                ],
            ],
            'accessor' => '\\App\\Services\\Accessors\\ServiceAccessorWithRPC',
        ],
    ],
    'from'   => 'shawn.zheng'
];
