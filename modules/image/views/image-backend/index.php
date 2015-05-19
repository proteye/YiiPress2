<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\image\models\ImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Images';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Image', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'file',
                'format' => 'html',
                'value' => function ($model) {
                        return Html::a(Html::img($model->thumbUrl, ['alt' => $model->alt]), $model->fileUrl, ['class' => 'prettyphoto']);
                    },
            ],
            'file',
            'name',
            'alt',
            // 'description:ntext',
            // 'category_id',
            // 'parent_id',
            'created_at:date',
            // 'updated_at:date',
            // 'user_id',
            // 'sort',
            // 'type',
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
