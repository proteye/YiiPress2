<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\core\models\Setting */

$this->title = $model->param_key;
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'module_id' => $model->module_id, 'param_key' => $model->param_key], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'module_id' => $model->module_id, 'param_key' => $model->param_key], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'module_id',
            'param_key',
            'param_value',
            'user_id',
            'created_at:date',
            'updated_at:date',
            'type',
        ],
    ]) ?>

</div>
