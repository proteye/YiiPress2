<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\page\models\Page */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'parent_id',
            'category_id',
            'lang',
            'slug:url',
            'alias',
            'title',
            'content:ntext',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'layout',
            'view',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'sort',
            'access_type',
            'status',
        ],
    ]) ?>

</div>
