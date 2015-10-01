<?php
namespace app\modules\coupon\widgets;

use Yii;
use yii\base\Widget;

class ScreenshotBrand extends Widget
{
    /**
     * @var \app\modules\coupon\models\CouponBrand
     */
    public $couponBrand;

    public function run()
    {
        return $this->render('screenshot-brand', ['model' => $this->couponBrand]);
    }
}