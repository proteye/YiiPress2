<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Обновить меню';
$this->params['breadcrumbs'][] = ['label' => 'Купоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="menu-form">

        <?= Html::beginForm('/backend/coupon/coupon-backend/menu-update', 'post') ?>

            <div class="form-group field-menu-id">
                <label class="control-label" for="menu-id">Меню</label>
                <?= Html::dropDownList('menu_id', 2, \app\modules\menu\models\Menu::getItemsList(), ['id' => 'menu-id', 'class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Обновить', ['class' => 'btn btn-success']) ?>
            </div>

        <?= Html::endForm() ?>

    </div>
</div>
