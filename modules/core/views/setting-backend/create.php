<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\core\models\Setting */

$this->title = 'Новый параметр';
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
