<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\CouponBrand */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-brand-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
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
            'id',
            'advcampaign_id',
            'slug',
            'name',
            'sec_name',
            'short_description',
            'description:ntext',
            'image',
            'image_alt',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'title',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'site:url',
            'advlink:url',
            'view_count',
            'status',
        ],
    ]) ?>

</div>
