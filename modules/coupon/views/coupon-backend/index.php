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

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Типы', ['/coupon/coupon-type-backend'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return $model->category_id ? \app\modules\category\models\Category::findOne($model->category_id)->name : '-';
                },
            ],
            'title',
            'url:url',
            // 'description:ntext',
            // 'type_id',
            // 'value',
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
            // 'view_count',
            // 'recommended',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
