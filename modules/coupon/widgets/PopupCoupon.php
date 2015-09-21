<?php
namespace app\modules\coupon\widgets;

use Yii;
use yii\base\Widget;

class PopupCoupon extends Widget
{
    public $coupon;

    public function run()
    {
        if ($this->coupon->promocode != null) {
            return $this->render('popup-promocode', ['coupon' => $this->coupon]);
        } else {
            return $this->render('popup-action', ['coupon' => $this->coupon]);
        }
    }
}