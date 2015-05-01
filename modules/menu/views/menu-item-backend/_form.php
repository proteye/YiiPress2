<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\MenuItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->textInput() ?>

    <?= $form->field($model, 'menu_id')->textInput() ?>

    <?= $form->field($model, 'regular_link')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'href')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'class')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'title_attr')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'before_link')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'after_link')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'target')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'rel')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'condition_name')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'condition_denial')->textInput() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
