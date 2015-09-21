<?php
use yii\helpers\Html;

/**
 * @var app\modules\coupon\models\Coupon $coupon
 */
?>

<?php if ($coupon): ?>
    <!-- noindex -->
    <div class="ui small image modal promocode-popup" id="<?= $coupon->id ?>">
        <i class="close icon"></i>
        <div class="header"><?= $coupon->name ?></div>
        <div class="image content">
            <?= Html::img($coupon->brand->imageUrl, ['alt' => $coupon->brand->image_alt, 'class' => 'image']) ?>
            <div class="description">
                <p><?= $coupon->filteredDescription ?></p>
            </div>
        </div>
        <div class="content action-popup-content">
            <div class="promocode-popup-action">
                <?= Html::a('Участвовать в акции и перейти на сайт магазина <i class="chevron right icon"></i>',
                    $coupon->gotolink,
                    ['class' => 'ui large fluid orange button', 'target' => '_blank', 'rel' => 'nofollow']
                ) ?>
            </div>
            <div class="promocode-popup-extra">
                <i class="alarm outline right icon"></i> Прочтите условия акции и перейдите на сайт магазина. Ввод промокода не требуется.
            </div>
        </div>
    </div>
    <!--/ noindex -->
<?php endif; ?>