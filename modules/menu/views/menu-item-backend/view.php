<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\MenuItem */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Пункты меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-view">

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
            'parent_id',
            'menu_id',
            'regular_link',
            'title',
            'href',
            'class',
            'title_attr',
            'before_link',
            'after_link',
            'target',
            'rel',
            'condition_name',
            'condition_denial',
            'sort',
            'status',
        ],
    ]) ?>

</div>
