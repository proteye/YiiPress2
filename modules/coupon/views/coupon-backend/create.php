<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\coupon\models\Coupon */

$this->title = 'Новый купон';
$this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
