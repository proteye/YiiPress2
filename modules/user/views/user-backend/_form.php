<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?= $model->hasErrors() ?
        FlashMessage::widget([
            'type' => FlashMessage::ERROR_MESSAGE,
            'message' => Html::errorSummary($model),
        ]) :
        null
    ?>

    <?= $profile->hasErrors() ?
        FlashMessage::widget([
            'type' => FlashMessage::ERROR_MESSAGE,
            'message' => Html::errorSummary($profile),
        ]) :
        null
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($profile, 'nick_nm')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($profile, 'first_nm')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($profile, 'last_nm')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($profile, 'patron')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($profile, 'about')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($profile, 'birth_dt')->widget(\yii\jui\DatePicker::classname(), ['language' => 'ru', 'dateFormat' => 'dd-MM-yyyy', 'clientOptions' => ['showAnim'=>'slideDown', 'showButtonPanel' => true]]) ?>

    <?= $form->field($profile, 'address')->textInput(['maxlength' => 512]) ?>

    <?= $form->field($profile, 'site')->textInput(['maxlength' => 255]) ?>

    <?php //$form->field($profile, 'avatar')->textInput(['maxlength' => 255]) ?>

    <?= $model->getIsNewRecord() ? $form->field($model, 'password')->passwordInput(['maxlength' => 255]) : null ?>

    <?= $model->getIsNewRecord() ? $form->field($model, 'password2')->passwordInput(['maxlength' => 255]) : null ?>

    <?= $form->field($profile, 'email_confirm')->dropDownList($profile->getStatusesArray()) ?>

    <?= ($model->id === 1)
        ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['disabled' => true])
        : $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
