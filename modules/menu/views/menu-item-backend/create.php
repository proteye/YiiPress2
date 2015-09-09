<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\MenuItem */

$this->title = 'Добавить пункт';
$this->params['breadcrumbs'][] = ['label' => 'Пункты меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
