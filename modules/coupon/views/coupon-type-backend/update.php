<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\CouponType */

$this->title = 'Изменить тип купона: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы купонов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="coupon-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
