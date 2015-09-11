<?php

namespace app\modules\coupon;

class Module extends \yii\base\Module
{
    const VERSION = '0.1.5';

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
            '<module:(coupon)>/search'=>'<module>/coupon-frontend/search',
            '<module:(coupon)>/<action:(new|best)>'=>'<module>/coupon-frontend/new-best',
            '<module:(coupon)>/shops'=>'<module>/coupon-frontend/brands',
            '<module:(coupon)>/categories'=>'<module>/coupon-frontend/categories',
            '<module:(coupon)>/cat-<category:[\w-]+>'=>'<module>/coupon-frontend/category',
            '<module:(coupon)>/<brand:[\w-]+>/<coupon:[\w-]+>'=>'<module>/coupon-frontend/default',
            '<module:(coupon)>/<brand:[\w-]+>'=>'<module>/coupon-frontend/brand',
        ];
    }
}
