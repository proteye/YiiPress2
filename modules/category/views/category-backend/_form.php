<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\core\widgets\FlashMessage;
use vova07\imperavi\Widget as Redactor;
use yii\helpers\Url;
use app\modules\category\models\CategoryType;

/* @var $this yii\web\View */
/* @var $model app\modules\category\models\Category */
/* @var $form yii\widgets\ActiveForm */

$core = Yii::$app->getModule('core');
?>

<div class="category-form">

    <?= $model->hasErrors() ?
        FlashMessage::widget([
            'type' => FlashMessage::ERROR_MESSAGE,
            'message' => Html::errorSummary($model),
        ]) :
        null
    ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'parent_id')->dropDownList($model->getParentsList($model->id), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'module')->dropDownList($core->getModulesList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'lang')->dropDownList($core->getLanguagesList()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'description')->widget(Redactor::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 400,
            'imageUpload' => '/image/image-backend/upload',
            'imageManagerJson' => Url::to(['/image/image-backend/image-get']),
            'plugins' => [
                'imagemanager',
                'clips',
                'fullscreen',
            ]
        ]
    ]); ?>

    <?= $form->field($model, 'short_description')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::img($model->image ? $model->getImageUrl() : '#', ['alt' => $model->image_alt, 'title' => $model->image_alt, 'class' => 'image-preview']) ?>
    </div>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'image_alt')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'type_id')->dropDownList(CategoryType::getItemsList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
