<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\coupon\models\CouponBrand;
use vova07\imperavi\Widget as Redactor;
use yii\helpers\Url;
use app\modules\coupon\models\CouponType;

/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">

    <?= $model->hasErrors() ?
        \app\modules\core\widgets\FlashMessage::widget([
            'type' => \app\modules\core\widgets\FlashMessage::ERROR_MESSAGE,
            'message' => Html::errorSummary($model),
        ]) :
        null
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'brand_id')->dropDownList(CouponBrand::getItemsList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'adv_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'promocode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'promolink')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gotolink')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_id')->dropDownList(CouponType::getItemsList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(Redactor::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 100,
            'imageUpload' => Url::to(['/image/image-backend/upload']),
            'imageManagerJson' => Url::to(['/image/image-backend/image-get']),
            'plugins' => [
                'imagemanager',
                'video',
                'clips',
                'fullscreen',
            ]
        ]
    ]); ?>

    <?= $form->field($model, 'begin_dt')->widget(\yii\jui\DatePicker::classname(), ['language' => 'ru', 'dateFormat' => 'dd-MM-yyyy', 'clientOptions' => ['showAnim'=>'slideDown', 'showButtonPanel' => true]]) ?>

    <?= $form->field($model, 'end_dt')->widget(\yii\jui\DatePicker::classname(), ['language' => 'ru', 'dateFormat' => 'dd-MM-yyyy', 'clientOptions' => ['showAnim'=>'slideDown', 'showButtonPanel' => true]]) ?>

    <?= $form->field($model, 'recommended')->checkbox() ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
