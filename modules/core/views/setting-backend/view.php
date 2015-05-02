<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\core\models\Setting */

$this->title = $model->module_id;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'module_id' => $model->module_id, 'param_key' => $model->param_key], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'module_id' => $model->module_id, 'param_key' => $model->param_key], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
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
            'created_at',
            'updated_at',
            'type',
        ],
    ]) ?>

</div>
