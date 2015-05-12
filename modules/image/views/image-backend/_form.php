<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\category\models\Category;
use app\modules\core\widgets\FlashMessage;

/* @var $this yii\web\View */
/* @var $model app\modules\image\models\Image */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-form">

    <?= $model->hasErrors() ?
        FlashMessage::widget([
            'type' => FlashMessage::ERROR_MESSAGE,
            'message' => Html::errorSummary($model),
        ]) :
        null
    ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'category_id')->dropDownList(Category::getItemsList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'parent_id')->dropDownList($model->getParentsList($model->id), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::img($model->file ? $model->getImageUrl() : '#', ['alt' => $model->alt, 'title' => $model->alt, 'class' => 'image-preview']) ?>
    </div>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'alt')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->dropDownList($model->getTypesArray()) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
