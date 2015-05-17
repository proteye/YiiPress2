<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

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
            'category_id',
            'lang',
            'slug',
            'title',
            'quote',
            'content:ntext',
            'image',
            'image_alt',
            'created_by',
            'updated_by',
            'created_at:date',
            'updated_at:date',
            'published_at:date',
            'user_ip',
            'link',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'access_type',
            'comment_status',
            'status',
            'view_count',
        ],
    ]) ?>

</div>
