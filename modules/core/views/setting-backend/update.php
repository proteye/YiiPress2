<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\core\models\Setting */

$this->title = 'Изменить параметр: ' . ' ' . $model->param_key;
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->param_key, 'url' => ['view', 'module_id' => $model->module_id, 'param_key' => $model->param_key]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="setting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
