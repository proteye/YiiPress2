<?php

use yii\helpers\Html;
use app\assets\BackendAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Импорт';
$this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$html = Html::img($this->assetManager->getBundle(BackendAsset::className())->baseUrl . '/img/AjaxLoader.gif');
$script = <<<JS
$("#import-form").submit(function() {
    $("#ajax-upload").html('$html');
    $("#import-btn").attr('disabled', 'disabled');
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>
<div class="import-csv">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="import-csv-form">

        <?= Html::beginForm('/backend/coupon/coupon-backend/import-csv', 'post', ['enctype' => 'multipart/form-data', 'id' => 'import-form']) ?>

            <div class="form-group field-file-csv">
                <label class="control-label" for="file-csv">Файл csv</label>
                <?= Html::fileInput('file-csv', null, ['id' => 'file-csv']) ?>
            </div>

            <div class="form-group" id="ajax-upload"></div>

            <div class="form-group">
                <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success', 'id' => 'import-btn']) ?>
            </div>

        <?= Html::endForm() ?>

    </div>
</div>
