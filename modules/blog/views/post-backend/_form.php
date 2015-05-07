<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\category\models\Category;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Post */
/* @var $form yii\widgets\ActiveForm */

$core = Yii::$app->getModule('core');
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'category_id')->dropDownList(Category::getItemsList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'lang')->dropDownList($core->getLanguagesList()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'quote')->textInput(['maxlength' => 512]) ?>

    <div class="form-group">
        <?= Html::img($model->image ? $model->getImageUrl() : '#', ['alt' => $model->image_alt, 'title' => $model->image_alt, 'class' => 'image-preview']) ?>
    </div>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'published_at')->widget(\yii\jui\DatePicker::classname(), ['language' => 'ru', 'dateFormat' => 'dd-MM-yyyy', 'clientOptions' => ['showAnim'=>'slideDown', 'showButtonPanel' => true]]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'access_type')->dropDownList($model->getAccessesArray()) ?>

    <?= $form->field($model, 'comment_status')->dropDownList($model->getCommentStatusesArray()) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
