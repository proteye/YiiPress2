<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\core\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'module_id')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'param_key')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'param_value')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'type')->dropDownList($model->getTypesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
