<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\coupon\models\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Купоны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="pull-left">
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Типы', ['/coupon/coupon-type-backend'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Бренды', ['/coupon/coupon-brand-backend'], ['class' => 'btn btn-danger']) ?>
    </div>
    <div class="pull-right">
        <?= Html::a('Создать меню', ['/coupon/coupon-backend/menu-create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Обновить меню', ['/coupon/coupon-backend/menu-update'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Импорт', ['/coupon/coupon-backend/import-csv'], ['class' => 'btn btn-warning']) ?>
    </div>
    <div class="clearfix"></div>
    <p></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'brand_id',
                'value' => function ($model) {
                    return $model->brand_id ? \app\modules\coupon\models\CouponBrand::findOne($model->brand_id)->name : '-';
                },
            ],
            'adv_id',
            // 'slug',
            'name',
            // 'short_name',
            'description:ntext',
            'promocode',
            // 'promolink:url',
            'gotolink:url',
            // 'type_id',
            // 'discount',
            'begin_dt:date',
            'end_dt:date',
            // 'created_by',
            // 'updated_by',
            // 'created_at',
            // 'updated_at',
            // 'meta_title',
            // 'meta_keywords',
            // 'meta_description',
            // 'user_ip',
            // 'recommended',
            // 'view_count',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusName;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
