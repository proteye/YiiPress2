<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\blog\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'lang',
            'title',
            'slug',
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                        return $model->category_id ? \app\modules\category\models\Category::findOne($model->category_id)->name : '-';
                    },
            ],
            // 'quote',
            // 'content:ntext',
            // 'image',
            // 'image_alt',
            // 'created_by',
            // 'updated_by',
            // 'created_at',
            // 'updated_at',
            'published_at:date',
            // 'user_ip',
            // 'link',
            // 'meta_title',
            // 'meta_keywords',
            // 'meta_description',
            // 'access_type',
            // 'comment_status',
            // 'status',
            // 'view_count',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
