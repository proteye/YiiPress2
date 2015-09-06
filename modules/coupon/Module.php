<?php

namespace app\modules\coupon;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.6';

    public $controllerNamespace = 'app\modules\coupon\controllers';

    public $uploadPath = 'coupon';

    public $cacheId = 'couponCID';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
