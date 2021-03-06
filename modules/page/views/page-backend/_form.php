<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\category\models\Category;
use vova07\imperavi\Widget as Redactor;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\page\models\Page */
/* @var $form yii\widgets\ActiveForm */

$core = Yii::$app->getModule('core');
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->dropDownList($model->getParentsList($model->id), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'category_id')->dropDownList(Category::getItemsList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'lang')->dropDownList($core->getLanguagesList()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'content')->widget(Redactor::className(), [
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

    <?= $form->field($model, 'alias')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'layout')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'view')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'access_type')->dropDownList($model->getAccessesArray()) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
