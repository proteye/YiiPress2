<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\menu\models\MenuItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пункты меню';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-index">

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
            [
                'attribute' => 'menu_id',
                'value' => function ($model) {
                    return $model->menu_id ? \app\modules\menu\models\Menu::findOne($model->menu_id)->name : '-';
                },
            ],
            [
                'attribute' => 'parent_id',
                'value' => function ($model) {
                    return $model->parent_id ? \app\modules\menu\models\MenuItem::findOne($model->parent_id)->title : '-';
                },
            ],
            'title',
            'href',
            //'regular_link',
            // 'class',
            // 'title_attr',
            // 'before_link',
            // 'after_link',
            // 'target',
            // 'rel',
            // 'condition_name',
            // 'condition_denial',
            // 'sort',
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
