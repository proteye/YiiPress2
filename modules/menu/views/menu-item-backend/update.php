<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\MenuItem */

$this->title = 'Изменить пункт: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Пункты меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="menu-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
