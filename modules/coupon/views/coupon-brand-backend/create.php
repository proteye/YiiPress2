<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\CouponBrand */

$this->title = 'Добавить бренд';
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-brand-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
