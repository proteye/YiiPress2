<?php
use yii\helpers\Html;

/**
 * @var app\modules\coupon\models\Coupon $coupon
 */
?>

<?php if ($coupon): ?>
    <!-- noindex -->
    <div class="ui small modal promocode-popup" id="<?= $coupon->id ?>">
        <i class="close icon"></i>
        <div class="header"><?= $coupon->name ?></div>
        <div class="content">
            <div class="ui stackable column centered grid promocode-popup-field">
                <div class="eight wide column center aligned promocode-popup-code"><?= $coupon->promocode ?></div>
                <div class="five wide column computer only promocode-popup-button"><?= Html::button('<i class="copy icon"></i> Копировать код', [
                        'class' => 'ui fluid medium button promocode-button-copy',
                    ]) ?></div>
            </div>
            <div class="promocode-popup-description">
                <p><?= $coupon->filteredDescription ?></p>
            </div>
            <div class="promocode-popup-action">
                <?= Html::a('Перейти на сайт магазина и использовать промокод <i class="chevron right icon"></i>',
                    $coupon->gotolink,
                    ['class' => 'ui large fluid green button', 'target' => '_blank', 'rel' => 'nofollow']
                ) ?>
            </div>
            <div class="promocode-popup-extra">
                <i class="alarm outline right icon"></i> Скопируйте код, перейдите на сайт магазина и введите его во время оформления заказа.
            </div>
        </div>
    </div>
    <!--/ noindex -->
<?php endif; ?>