<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\CouponType */

$this->title = 'Create Coupon Type';
$this->params['breadcrumbs'][] = ['label' => 'Coupon Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
