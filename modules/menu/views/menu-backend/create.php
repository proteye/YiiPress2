<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\Menu */

$this->title = 'Добавить меню';
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
