<?php
use yii\helpers\Html;
use app\modules\image\helpers\ImageHelper;

/**
 * @var app\modules\coupon\models\CouponBrand $model
 */

?>

<?php if ($model): ?>

    <div class="ui raised segment">
        <div class="ui image coupon-sidebar-screenshot">
            <?= Html::a(
                ImageHelper::siteShot($model->site, $model->name),
                $model->golink,
                ['target' => '_blank']
            ) ?>
        </div>
        <div class="coupon-sidebar-extra">
            <div class="ui grid">
                <div class="five wide column right aligned">
                    <div class="coupon-count">
                        <?= $model->couponsCount ?>
                    </div>
                </div>
                <div class="eleven wide column">
                    Действующих предложений на сегодня от магазина <?= $model->name ?> (акции, купоны и скидки) на
                    <?= strftime('%B', time()) ?> - <?= strftime('%B %Y', strtotime('now +1 month')) ?>
                </div>
            </div>
        </div>
        <div class="ui clearing divider"></div>
        <div class="coupon-sidebar-description"><?= $model->short_description ?></div>
    </div>

<?php endif; ?>