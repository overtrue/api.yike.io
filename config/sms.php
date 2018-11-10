<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'timeout' => 5.0,
    'default' => [
        'strategy' => RandomStrategy::class,
        'gateways' => [
            'yunpian',
        ],
    ],
    'gateways' => [
        /*
         * PHP error log gateway
         *
         * http://php.net/manual/en/function.error-log.php
         */
        'error-log' => [
            'file' => '/tmp/easy-sms.log',
        ],
        /*
         * Yun Pian SMS service.
         *
         * https://www.yunpian.com/
         */
        'yunpian' => [
            'api_key' => env('YUNPIAN_API_KEY'),
        ],
    ],
];
