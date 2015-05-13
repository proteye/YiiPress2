<?php

$config = [
    'id' => 'app',
    'defaultRoute' => 'core/core-frontend/index',
    'aliases' => [
        '@core' => '@app/modules/core', // Core path
        '@theme' => '@app/themes/default', // default path to Theme
    ],
    'bootstrap' => [
        /* Set current Theme */
        [
            'class' => 'app\modules\core\components\CoreBootstrap',
            'theme' => '', // if empty then get Theme from database
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'loginUrl' => ['user/user/login'],
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'core/core-frontend/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ],
        'view' => [
            /* default Theme */
            'theme' => [
                'basePath' => '@theme',
                'baseUrl' => '@web/themes/default',
                'pathMap' => [
                    '@app/views' => '@theme',
                    '@app/modules' => '@theme/modules',
                ],
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
    ];
}

return $config;
