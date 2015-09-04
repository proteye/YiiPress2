<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\category\models\Category;
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

    <?= $form->field($model, 'category_id')->dropDownList(Category::getItemsList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(Redactor::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 400,
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

    <?= $form->field($model, 'type_id')->dropDownList(CouponType::getItemsList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'begin_dt')->widget(\yii\jui\DatePicker::classname(), ['language' => 'ru', 'dateFormat' => 'dd-MM-yyyy', 'clientOptions' => ['showAnim'=>'slideDown', 'showButtonPanel' => true]]) ?>

    <?= $form->field($model, 'end_dt')->widget(\yii\jui\DatePicker::classname(), ['language' => 'ru', 'dateFormat' => 'dd-MM-yyyy', 'clientOptions' => ['showAnim'=>'slideDown', 'showButtonPanel' => true]]) ?>

    <?php // $form->field($model, 'created_by')->textInput() ?>

    <?php // $form->field($model, 'updated_by')->textInput() ?>

    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?php // $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'recommended')->dropDownList($model->getRecommendedsArray()) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'user_ip')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'view_count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
