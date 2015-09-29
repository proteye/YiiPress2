<?php
namespace app\modules\coupon\widgets;

use Yii;
use yii\base\Widget;
use app\modules\coupon\models\CouponBrand;

class TopBrands extends Widget
{
    private $cacheId = 'topBrands';
    public $brand_id = null;
    public $limit = 10;

    public function run()
    {
        $core = Yii::$app->getModule('core');

        if ($this->brand_id !== null) {
            $cacheId = $this->cacheId . '_' . $this->brand_id;
        } else {
            $cacheId = $this->cacheId;
        }

        $model = Yii::$app->cache[$cacheId];
        if ($model === false) {
            $model = CouponBrand::getTopBrands($this->limit, $this->brand_id);
            Yii::$app->cache->set($cacheId, $model, $core->cacheTime);
        }

        return $this->render('top-brands', ['model' => $model]);
    }
}