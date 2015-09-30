<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var app\modules\coupon\models\CouponBrand[] $model
 */

?>

<?php if ($model): ?>

    <div class="widget">
        <div class="ui large header">
            <i class="shop icon"></i>
            <div class="content">Похожие магазины</div>
        </div>
        <div class="ui raised segment widget">
            <div class="ui two column stackable grid">
                <?php foreach ($model as $brand): ?>
                    <div class="column center aligned coupon-dashed">
                        <div class="ui floating orange circular label brand-label"><?= $brand->couponsCount ?></div>
                        <div class="ui image">
                            <?= Html::a(
                                Html::img($brand->imageUrl, ['alt' => $brand->image_alt]),
                                Url::to(['coupon-frontend/brand', 'brand' => $brand->slug])
                            ) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php endif; ?>