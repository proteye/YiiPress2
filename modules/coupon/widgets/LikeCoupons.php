<?php
namespace app\modules\coupon\widgets;

use Yii;
use yii\base\Widget;
use app\modules\coupon\models\Coupon;
use app\modules\coupon\models\CouponBrand;
use yii\helpers\ArrayHelper;

class LikeCoupons extends Widget
{
    private $cacheId = 'likeCoupons';
    public $brand_id;
    public $limit = 10;

    public function run()
    {
        $core = Yii::$app->getModule('core');
        $cacheId = $this->cacheId . '_' . $this->brand_id;

        $model = Yii::$app->cache[$cacheId];
        if ($model === false) {
            $model = Coupon::getLikeCoupons($this->brand_id, $this->limit);
            Yii::$app->cache->set($cacheId, $model, $core->cacheTime);
        }

        return $this->render('like-coupons', ['model' => $model]);
    }
}