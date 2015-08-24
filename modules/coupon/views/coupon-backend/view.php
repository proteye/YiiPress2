<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\Coupon */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Coupons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-view">

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
            'title',
            'url:url',
            'description:ntext',
            'type_id',
            'value',
            'begin_dt',
            'end_dt',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'user_ip',
            'view_count',
            'recommended',
            'status',
        ],
    ]) ?>

</div>
