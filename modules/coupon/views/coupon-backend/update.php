<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\Coupon */

$this->title = 'Изменить купон: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="coupon-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
