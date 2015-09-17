<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\category\models\Category;
use vova07\imperavi\Widget as Redactor;
use kartik\widgets\Select2;
use app\modules\core\widgets\FlashMessage;

/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\CouponBrand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-brand-form">

    <?= $model->hasErrors() ?
        FlashMessage::widget([
            'type' => FlashMessage::ERROR_MESSAGE,
            'message' => Html::errorSummary($model),
        ]) :
        null
    ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'categories')->widget(Select2::classname(), [
        'data' => Category::getItemsList(Yii::$app->controller->module->id),
        'options' => ['multiple' => true],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => false,
            'tokenSeparators' => [','],
            'maximumInputLength' => 255,
        ],
    ]); ?>

    <?= $form->field($model, 'advcampaign_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sec_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'advlink')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(Redactor::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
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

    <div class="form-group">
        <?= Html::img($model->image ? $model->getImageUrl() : '#', ['alt' => $model->image_alt, 'title' => $model->image_alt]) ?>
    </div>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'image_alt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
