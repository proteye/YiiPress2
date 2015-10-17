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
        'catalog' => [
            'class' => 'app\modules\catalog\Module',
        ],
        'coupon' => [
            'class' => 'app\modules\coupon\Module',
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
        'mutex' => [
            'class' => 'yii\mutex\FileMutex'
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                ''                                                                                   => 'page/page-frontend/index',
                'sitemap'                                                                            => 'core/sitemap/index',
                'sitemap.xml'                                                                        => 'core/sitemap/sitemap-xml',
                'contact'                                                                            => 'core/contact/index',
                'search'                                                                             => 'core/core-frontend/search',
                'tag/<url:[\w\-\/]+>'                                                                => 'blog/blog-frontend/tag',
                '<url:[\w\-\/]+>'                                                                    => 'blog/blog-frontend/route',
            ]
        ],
    ],
    'params' => $params,
];
