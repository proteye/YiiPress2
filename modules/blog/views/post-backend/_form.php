<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\category\models\Category;
use vova07\imperavi\Widget as Redactor;
use yii\helpers\Url;
use app\modules\core\widgets\FlashMessage;
use kartik\widgets\Select2;
use app\modules\blog\models\Tag;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Post */
/* @var $form yii\widgets\ActiveForm */

$core = Yii::$app->getModule('core');
?>

<div class="post-form">

    <?= $model->hasErrors() ?
        FlashMessage::widget([
            'type' => FlashMessage::ERROR_MESSAGE,
            'message' => Html::errorSummary($model),
        ]) :
        null
    ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'category_id')->dropDownList(Category::getItemsList(), ['prompt' => '-- нет --']) ?>

    <?= $form->field($model, 'lang')->dropDownList($core->getLanguagesList()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => 160]) ?>

    <?= $form->field($model, 'published_at')->widget(\yii\jui\DatePicker::classname(), ['language' => 'ru', 'dateFormat' => 'dd-MM-yyyy', 'clientOptions' => ['showAnim'=>'slideDown', 'showButtonPanel' => true]]) ?>

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

    <?= $form->field($model, 'quote')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::img($model->image ? $model->getImageUrl() : '#', ['alt' => $model->image_alt, 'title' => $model->image_alt, 'class' => 'image-preview']) ?>
    </div>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'image_alt')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'tags')->widget(Select2::classname(), [
        'data' => Tag::getItemsList(),
        'options' => ['multiple' => true],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => true,
            'tokenSeparators' => [','],
            'maximumInputLength' => 160,
        ],
    ]); ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'created_by')->dropDownList(\app\modules\user\models\User::getUsersArray()) ?>

    <?= $form->field($model, 'access_type')->dropDownList($model->getAccessesArray()) ?>

    <?= $form->field($model, 'comment_status')->dropDownList($model->getCommentStatusesArray()) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
