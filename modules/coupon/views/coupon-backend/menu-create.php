<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\core\widgets\FlashMessage;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Новое меню';
$this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="menu-form">

        <?= $model->hasErrors() ?
            FlashMessage::widget([
                'type' => FlashMessage::ERROR_MESSAGE,
                'message' => Html::errorSummary($model),
            ]) :
            null
        ?>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => 160]) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
