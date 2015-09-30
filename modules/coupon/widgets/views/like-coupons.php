<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var app\modules\coupon\models\Coupon[] $model
 */

?>

<?php if ($model): ?>

    <div class="widget">
        <div class="ui large header">
            <i class="tags icon"></i>
            <div class="content">Похожие купоны</div>
        </div>
        <div class="ui raised segment widget">
            <div class="ui one column stackable grid">
                <?php foreach ($model as $coupon): ?>
                    <div class="column center aligned coupon-dashed">
                        <div class="ui image coupon-like-image">
                            <?= Html::a(
                                Html::img($coupon->brand->imageUrl, ['alt' => $coupon->brand->image_alt]),
                                Url::to(['coupon-frontend/brand', 'brand' => $coupon->brand->slug])
                            ) ?>
                        </div>
                        <div class="content coupon-like-content">
                            <div class="coupon-like-header">
                                <?= Html::a(
                                    $coupon->short_name,
                                    Url::to(['coupon-frontend/default', 'brand' => $coupon->brand->slug, 'coupon' => $coupon->slug]),
                                    ['class' => 'link-black']
                                ) ?>
                            </div>
                            <div class="coupon-like-extra">
                                Действителен до <?= date('d.m.Y', $coupon->end_dt) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php endif; ?>