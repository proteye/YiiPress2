<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\coupon\models\CouponTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы купонов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-type-index">

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

            'id',
            'name',
            'slug',
            'extra',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
