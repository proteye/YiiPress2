<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\coupon\models\CouponBrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Бренды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-brand-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Купоны', ['/coupon/coupon-backend'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->image ? Html::img($model->thumbUrl, ['alt' => $model->image_alt]) : null;
                },
            ],
            'advcampaign_id',
            'name',
            'sec_name',
            'slug',
            'site:url',
            // 'short_description',
            // 'description:ntext',
            // 'image_alt',
            // 'created_by',
            // 'updated_by',
            // 'created_at',
            // 'updated_at',
            // 'title',
            // 'meta_title',
            // 'meta_keywords',
            // 'meta_description',
            'advlink:url',
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
