<?php
use yii\helpers\ArrayHelper;

$params = ArrayHelper::merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);


return [
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'core' => [
            'class' => 'app\modules\core\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'log' => [
            'class' => 'yii\log\Dispatcher',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                ''                                                                                   => 'core/core/index',
                'contact'                                                                            => 'core/contact/index',
                '<action:(about)>'                                                                   => 'core/core/<action>',
                '<action:(login|logout|signup|confirm-email|request-password-reset|reset-password)>' => 'user/user/<action>',
                '<module:\w+>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>'                        => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:[\w\-]+>/<action:[\w\-]+>'                                 => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:[\w\-]+>/<id:\d+>'                                         => '<module>/<controller>/view',
                '<module:\w+>/<controller:[\w\-]+>'                                                  => '<module>/<controller>/index',
            ]
        ],
    ],
    'params' => $params,
];
