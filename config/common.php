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
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'core' => [
            'class' => 'app\modules\core\Module',
        ],
        'category' => [
            'class' => 'app\modules\category\Module',
        ],
        'menu' => [
            'class' => 'app\modules\menu\Module',
        ],
        'image' => [
            'class' => 'app\modules\image\Module',
        ],
        'page' => [
            'class' => 'app\modules\page\Module',
        ],
        'blog' => [
            'class' => 'app\modules\blog\Module',
        ],
        'comment' => [
            'class' => 'app\modules\comment\Module',
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
                ''                                                                                   => 'core/core-frontend/index',
                'sitemap.xml'                                                                        => 'core/sitemap/index',
                'contact'                                                                            => 'core/contact/index',
                '<action:(search)>'                                                                  => 'core/core-frontend/<action>',
                '<action:(login|logout|signup|confirm-email|request-password-reset|reset-password)>' => 'user/user-frontend/<action>',
                'tag/<url:[\w\-\/]+>'                                                                => 'core/core-frontend/tag',
                '<url:(?!backend)[\w\-\/]+>'                                                                    => 'core/core-frontend/route',
                'backend/<module:\w+>/<controller:[\w\-]+>/<id:\d+>'                                 => '<module>/<controller>/view',
                'backend/<module:\w+>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>'                => '<module>/<controller>/<action>',
                'backend/<module:\w+>/<controller:[\w\-]+>/<action:[\w\-]+>'                         => '<module>/<controller>/<action>',
                'backend/<module:\w+>/<controller:[\w\-]+>'                                          => '<module>/<controller>',
            ]
        ],
    ],
    'params' => $params,
];
