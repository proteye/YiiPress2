<?php

namespace app\modules\coupon;

class Module extends \yii\base\Module
{
    const VERSION = '0.2.5';

    public $controllerNamespace = 'app\modules\coupon\controllers';

    public $uploadPath = 'coupon';

    public $cacheId = 'couponCID';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function rules()
    {
        return [
            'coupon/search'                         => 'coupon/coupon-frontend/search',
            'coupon/go/<id:[\w\d-]+>'               => 'coupon/coupon-frontend/go',
            'coupon/<action:(new|best)>'            => 'coupon/coupon-frontend/new-best',
            'coupon/shops'                          => 'coupon/coupon-frontend/brands',
            'coupon/categories'                     => 'coupon/coupon-frontend/categories',
            'coupon/cat-<category:[\w-]+>'          => 'coupon/coupon-frontend/category',
            'coupon/<brand:[\w-]+>/<coupon:[\w-]+>' => 'coupon/coupon-frontend/default',
            'coupon/<brand:[\w-]+>'                 => 'coupon/coupon-frontend/brand',
        ];
    }
}
