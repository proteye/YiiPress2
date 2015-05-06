<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'category_id')->textInput() ?>

    <?= $form->field($model, 'lang')->textInput(['maxlength' => 2]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'quote')->textInput(['maxlength' => 512]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::img($model->image ? $model->getImageUrl() : '#', ['alt' => $model->image_alt, 'title' => $model->image_alt, 'class' => 'image-preview']) ?>
    </div>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'create_user_id')->textInput() ?>

    <?= $form->field($model, 'update_user_id')->textInput() ?>

    <?= $form->field($model, 'published_at')->textInput() ?>

    <?= $form->field($model, 'create_user_ip')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'access_type')->textInput() ?>

    <?= $form->field($model, 'comment_status')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
