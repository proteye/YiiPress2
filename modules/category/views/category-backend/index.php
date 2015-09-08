<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\category\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'lang',
            [
                'attribute' => 'parent_id',
                'value' => function ($model) {
                    return $model->parent_id ? \app\modules\category\models\Category::findOne($model->parent_id)->name : '-';
                },
            ],
            'name',
            'slug',
            'module',
            // 'short_description',
            // 'description:ntext',
            // 'image',
            // 'image_alt',
            // 'created_by',
            // 'updated_by',
            // 'created_at',
            // 'updated_at',
            // 'meta_title',
            // 'meta_keywords',
            // 'meta_description',
            'view_count',
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
