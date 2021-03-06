<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\Coupon */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-view">

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
            'adv_id',
            'brand_id',
            'slug',
            'name',
            'short_name',
            'description:ntext',
            'promocode',
            'promolink:url',
            'gotolink:url',
            'type_id',
            'discount',
            'begin_dt:date',
            'end_dt:date',
            'created_by',
            'updated_by',
            'created_at:date',
            'updated_at:date',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'user_ip',
            'recommended',
            'view_count',
            'status',
        ],
    ]) ?>

</div>
