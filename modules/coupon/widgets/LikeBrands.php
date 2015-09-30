<?php
namespace app\modules\coupon\widgets;

use Yii;
use yii\base\Widget;
use app\modules\coupon\models\CouponBrand;

class LikeBrands extends Widget
{
    private $cacheId = 'likeBrands';
    public $brand_id;
    public $limit = 10;

    public function run()
    {
        $core = Yii::$app->getModule('core');
        $cacheId = $this->cacheId . '_' . $this->brand_id;

        $model = Yii::$app->cache[$cacheId];
        if ($model === false) {
            $model = CouponBrand::getLikeBrands($this->brand_id, $this->limit);
            Yii::$app->cache->set($cacheId, $model, $core->cacheTime);
        }

        return $this->render('like-brands', ['model' => $model]);
    }
}