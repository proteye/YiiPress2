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
        'base' => [
            'class' => 'app\modules\base\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
            'defaultRoute' => 'default'
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'loginUrl' => ['user/default/login'],
            'enableAutoLogin' => true,
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
                ''                                                                                   => 'base/default/index',
                'contact'                                                                            => 'base/contact/index',
                '<action:(about)>'                                                                   => 'base/default/<action>',
                '<action:(login|logout|signup|confirm-email|request-password-reset|reset-password)>' => 'user/default/<action>',
                '<module:\w+>'                                                                       => '<module>',
                '<module:\w+>/<controller:[\w\-]+>'                                                  => '<module>/<controller>/index',
                '<module:\w+>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>'                        => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:[\w\-]+>/<action:[\w\-]+>'                                 => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:[\w\-]+>/<id:\d+>'                                         => '<module>/<controller>/view',
                '<module:\w+>/<controller:[\w\-]+>'                                                  => '<module>/<controller>/index',
            ]
        ],
    ],
    'params' => $params,
];
